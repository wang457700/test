<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Shared_Date;
use PHPExcel_Style;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Style_NumberFormat;

/**
 * 共享平台
 * 用户发布的共享列表
 */
class Order extends Backend
{


    /**
     * 查看
     */
    public function index()
    {
       // sum_order_price('201809291538190190');
        $where   = [];
        $keywordComplex = [];
        $request = input('request.');
        $request['payment'] = input('payment','');
        $request['pay_status'] = input('pay_status','all');

        if (!empty($request['keyword'])) {
            $keyword = $request['keyword'];
            $keywordComplex['order_sn'] = ['like', "%$keyword%"];
        }
        if (!empty($request['payment'])) {
            $where['payment'] = $request['payment'];
        }
        if ($request['pay_status'] !='all') {
            $where['pay_status'] = $request['pay_status'];
        }
        $order_list = Db::name('order')->alias('a')

            //->join('__GOODS__ c', 'a.goods_id=c.product_id', 'LEFT')
            ->whereOr($keywordComplex)
            ->where($where)
            ->field('a.*,e.nickname,(select sum(money_total) from fa_order where order_sn=a.order_sn) as money_total')
            ->alias('a')
            ->join('__USER__ e', 'a.user_id=e.id', 'LEFT')
            ->group('a.order_sn')
            ->order('addtime desc')
            ->paginate(10,false,array('query'=>$request));


        //统计
        $money_total_sum = 0;
        $money_total_sum2 = 0;
        $order_count = Db::name('order')->where(array('pay_status'=>array('in','2,3,5,7')))->alias('a')->group('a.order_sn')->count();
        $goods_num_sum = Db::name('order')->where(array('pay_status'=>array('in','2,3,5,7')))->sum('goods_num');
        $money_total_list = Db::name('order')->where(array('pay_status'=>array('in','2,3,5,7'),'goods_is_inland'=>0))->field('a.*,(select sum(money_total) from fa_order where order_sn=a.order_sn) as money_total')->alias('a')->group('a.order_sn')->select();
        $money_total_list2 = Db::name('order')->where(array('pay_status'=>array('in','2,3,5,7'),'goods_is_inland'=>1))->field('a.*,(select sum(money_total) from fa_order where order_sn=a.order_sn) as money_total')->alias('a')->group('a.order_sn')->select();
        foreach ($money_total_list as $item){
            $money_total_sum += $item['money_total'];
        }
        foreach ($money_total_list2 as $item){
            $money_total_sum2 += $item['money_total'];
        }
        $sum = array('order_count'=>$order_count,'goods_num_sum'=>$goods_num_sum,'money_total_sum'=>$money_total_sum,'money_total_sum2'=>$money_total_sum2);
        //统计状态
        $count1 = Db::name('order')->where(array('pay_status'=>array('in','3,5')))->alias('a')->group('a.order_sn')->count();
        $count2 = Db::name('order')->where(array('pay_status'=>array('in','2,7')))->alias('a')->group('a.order_sn')->count();
        $count3 = Db::name('order')->where(array('pay_status'=>array('in','6')))->alias('a')->group('a.order_sn')->count();
        $pay_sum = array('count1'=>$count1,'count2'=>$count2,'count3'=>$count3);

        $pay_status = array('0'=>'未支付','2'=>'已支付','3'=>'已发货','6'=>'已取消','7'=>'到付');
        $page = $order_list->render();
        $this->assign('page', $page);
        $this->assign('order_list', $order_list);
        $this->assign('payment', sp_payment_array());

        $this->assign('request', $request);
        $this->assign('sum', $sum);
        $this->assign('pay_sum', $pay_sum);
        $this->assign('pay_status', $pay_status);

        return $this->view->fetch();
    }


    //导出订单
    public function export()
    {
        if ($this->request->isPost()) {
            set_time_limit(0);
            $ids = $this->request->post('ids');
            $keyword = $this->request->post('keyword');
            $payment = $this->request->post('payment');
            $pay_status = $this->request->post('pay_status');
            $columns = $this->request->post('columns');

            $excel = new PHPExcel();
            $excel->getProperties()
                ->setCreator("FastAdmin")
                ->setLastModifiedBy("FastAdmin")
                ->setTitle("标题")
                ->setSubject("Subject");
            $excel->getDefaultStyle()->getFont()->setName('Microsoft Yahei');
            $excel->getDefaultStyle()->getFont()->setSize(15);

            $this->sharedStyle = new PHPExcel_Style();
            $this->sharedStyle->applyFromArray(
                array(
                    'fill'      => array(
                        'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                       // 'color' => array('rgb' => '000000')
                    ),
                    'font'      => array(
                        // 'color' => array('rgb' => "000000"),
                    ),
                    'alignment' => array(
                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'indent'     => 1
                    ),
                    'borders'   => array(
                        'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    )
                ));

            $worksheet = $excel->setActiveSheetIndex(0);
            $worksheet->setTitle('标题');

            $whereIds = $ids == 'all' ? '1=1' : ['order_id' => ['in', explode(',', $ids)]];
           // $this->request->get(['search' => $search, 'ids' => $ids, 'filter' => $filter, 'op' => $op]);
         //   list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $line = 1;
            $list = [];
            $where = [];

            if (!empty($keyword)) {
                $where['order_sn'] = ['like', "%$keyword%"];
            }
            if (!empty($payment)) {
                $where['payment'] = $payment;
            }
            if ($pay_status !='all') {
                $where['pay_status'] = $pay_status;
            }
            Db::name('order')
                ->alias('a')
                ->field($columns)
                ->field('(select sum(money_total) from fa_order where order_sn=a.order_sn) as money_total')
                ->where($where)
                ->where($whereIds)
                ->group('a.order_sn')
                ->chunk(100, function ($items) use (&$list, &$line, &$worksheet) {
                    $payment = sp_payment_array();
                    $paystatus = sp_paystatus_array();
                    $styleArray = array(
                        'font' => array(
                            'bold'  => false,
                           // 'color' => array('rgb' => 'FF0000'),
                            'size'  => 12,
                            'name'  => 'Verdana'
                        ));
                    $list = $items = collection($items)->toArray();
                    foreach ($items as $index => $item) {
                        $item['payment'] = $payment[$item['payment']];
                        $item['pay_status'] = $paystatus[$item['pay_status']];
                        $item['order_sn'] = $item['order_sn'].' ';
                        $line++;
                        $col = 0;
                        foreach ($item as $field => $value) {
                            $worksheet->setCellValueByColumnAndRow($col, $line, $value);
                            $worksheet->getStyleByColumnAndRow($col, $line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                            $worksheet->getCellByColumnAndRow($col, $line)->getStyle()->applyFromArray($styleArray);
                            $col++;
                        }
                    }
                });
            $first = array_keys($list[0]);
            foreach ($first as $index => $item) {
                $worksheet->setCellValueByColumnAndRow($index, 1, __($item));
            }

            $excel->createSheet();
            // Redirect output to a client’s web browser (Excel2007)
            $title = date("YmdHis");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $objWriter->save('php://output');
            return;
        }
    }

    public function del($ids = NULL){
        $order_sn=input('order_sn');
        $res=Db::name('order')->where('order_sn',$order_sn)->delete();
        if($res){
            $this->success('删除成功');
        }
    }

    //发货
    public function deliver_order($ids = NULL){

        $order_sn=input('order_sn');
        $res=Db::name('order')->where('order_sn',$order_sn)->update(array('pay_status'=>'3'));
        if($res){
            $this->success('發貨成功');
        }
    }

    //取消訂單
    public function cancel_order($ids = NULL){
        $order_sn=input('order_sn');
        $res=Db::name('order')->where('order_sn',$order_sn)->update(array('pay_status'=>'6'));
        if($res){
            $this->success('取消訂單成功');
        }
    }


    //pre_order 商品列表
    public function pre_order_list(){

        $request = input('request.');
        $where = [];
        $keywordComplex = [];

        if (!empty($request['keyword'])) {
            $keyword = $request['keyword'];
            $keywordComplex['order_sn|product_name|goods_sn'] = ['like', "%$keyword%"];
        }

        $where['goods_is_pre_order'] = 1;
        $where['pay_status'] = array('in','1,2,3');
        $goods_list = Db::name('order')->alias('a')
            ->field('a.*,c.product_name,c.cover')
            ->join('__GOODS__ c', 'a.goods_id=c.product_id', 'LEFT')
            ->whereOr($keywordComplex)
            ->where($where)
           // ->field('a.*,e.nickname,(select sum(money_total) from fa_order where order_sn=a.order_sn) as money_total')
            ->alias('a')
            //->join('__USER__ e', 'a.user_id=e.id', 'LEFT')
            ->order('addtime desc')
            ->paginate(10,false,array('query'=>$request));
        $page = $goods_list->render();
        $this->assign('page', $page);
        $this->assign('goods_list', $goods_list);
        $this->assign('request', $request);
        $this->assign('paystatus', sp_paystatus_array());
        return $this->view->fetch();
    }


    public function detail(){
        $order_sn = input('order_sn');
        $order = Db::name('order')->alias('a')
        ->field('a.*,(select sum(money_total) from fa_order where order_sn=a.order_sn) as all_total,(select sum(goods_num) from fa_order where order_sn=a.order_sn) as all_goods_num,(select username from fa_user where id=a.user_id) as username,(select coupon_name from fa_coupon where coupon_id=a.coupon_id) as coupon_name,(select name from fa_user_address where id=a.address_id) as address_username,(select phone from fa_user_address where id=a.address_id) as address_phone,(select coupon_name from fa_coupon where coupon_id=a.coupon_id) as coupon_name')
        ->where('order_sn',$order_sn)->find();

        $goods_list = Db::name('order')
            ->alias('a')
            ->field('a.*,(select place_origin from fa_goods where product_id=a.goods_id) as place_origin,(select product_name from fa_goods where product_id=a.goods_id) as product_name')

            ->where('order_sn',$order_sn)->select();
        $this->view->assign("order", $order);
        $this->view->assign("goods_list", $goods_list);
        $this->view->assign("payment", sp_payment_array());
        $this->view->assign("pay_status", sp_paystatus_array());
        return $this->view->fetch();
    }
}