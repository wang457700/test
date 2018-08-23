<?php

namespace app\home\controller;

use app\common\controller\Frontend;
use app\common\library\Token;
use think\Db;

class News extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function _initialize()
    {

        parent::_initialize();
        $this->article_model = Db::name('article');
    }

    public function index()
    {   
        $where = array(
            'post_type'=>2, 
        );
        $list = $this->article_model
        ->where($where)
        ->select();
        $this->view->assign("list", $list);
        $this->assign('title','社區服務資訊');
        return $this->view->fetch();
    }


    public function article()
    {
        $articleId = $this->request->param('id', 0, 'intval');
        $data = $this->article_model
        ->where('id',$articleId)
        ->find();

        $prevArticle = $this->publishedPrevArticle($articleId);
        $nextArticle = $this->publishedNextArticle($articleId);
        $this->view->assign("data",$data);

        $this->assign('title',$data['post_title']);
        $this->view->assign("prev",$prevArticle);
        $this->view->assign("next",$nextArticle);
        return $this->view->fetch();
    }


  //上一篇文章
    public function publishedPrevArticle($postId, $categoryId = 0)
    {
             $where = array(
                'id' => array('lt',$postId)
            );

            $article = $this->article_model
                ->where($where)
                ->order('id', 'DESC')
                ->find();
        return $article;
    }

  //下一篇文章
    public function publishedNextArticle($postId, $categoryId = 0)
    {
             $where = array(
                'id' => array('gt',$postId)
            );

            $article = $this->article_model
                ->where($where)
                ->order('id', 'DESC')
                ->find();


        return $article;

    }
    

    public function news()
    {
        $newslist = [];
        return jsonp(['newslist' => $newslist, 'new' => count($newslist), 'url' => 'https://www.fastadmin.net?ref=news']);
    }





}
