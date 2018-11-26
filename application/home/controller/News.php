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
        $where['post_type'] = 2;
        $where['post_term_id'] = input('cid',64);
        $list = $this->article_model
        ->where($where)
        ->order('post_starttime desc')
        ->paginate(10);

        $name = Db::name('category')->where('id',input('cid',64))->value('name');
        $newscategory = sp_getTreeList(1);
        $this->view->assign("newscategory",$newscategory);
        $this->view->assign("list", $list);
        $this->view->assign("name",$name);
        $this->assign('title','社區服務資訊');
        return $this->view->fetch();
    }


    public function article()
    {
        $articleId = $this->request->param('id', 0, 'intval');
        $data = $this->article_model
        ->where('id',$articleId)
        ->find();

        $name = Db::name('category')->where('id',input('cid',64))->value('name');
        $prevArticle = $this->publishedPrevArticle($articleId,$data['post_term_id']);
        $nextArticle = $this->publishedNextArticle($articleId,$data['post_term_id']);
        $this->view->assign("data",$data);
        $this->assign('title',$data['post_title']);
        $this->view->assign("prev",$prevArticle);
        $this->view->assign("next",$nextArticle);
        $newscategory = sp_getTreeList(1);
        $this->view->assign("newscategory",$newscategory);
        $this->view->assign("name",$name);
        return $this->view->fetch();
    }

  //上一篇文章
    public function publishedPrevArticle($postId, $categoryId = 0)
    {
             $where = array(
                'id' => array('lt',$postId),
                 'post_term_id' => $categoryId,
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
                'id' => array('gt',$postId),
                'post_term_id' => $categoryId,
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
