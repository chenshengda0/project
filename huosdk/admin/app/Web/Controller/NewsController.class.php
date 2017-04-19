<?php
/**
* 新闻管理中心
*
* @author
*/
namespace Web\Controller;
use Common\Controller\AdminbaseController;

class NewsController extends AdminbaseController {
	
	protected $game_model;
	
	function _initialize() {
		parent::_initialize();
		$this->game_model = D("Common/Game");
	}

	/**
	 * 新闻列表
	 */
	public function findList(){
		$this->nList();
		$this -> display();
	}
	
	/**
	 * 查找新闻列表
	 */
	public function nList(){		
		$rows = I('rows',10); //每页显示条数，默认10
		$page = I('p');      //页码
		$title = I('name');  //新闻名
		
		//当接收分页时附加上上次搜索的游戏名,保证搜索条件不丢失。
		if($page && session('search_new_title')){
			$title = session('search_new_title');
			$map['title'] = array('like',"%$title%");
		}
		
		//组装分页搜索条件
		$map['is_delete'] = 2;
		if($title){
			$map['title'] = array('like',"%$title%");	//如果传了title,则覆盖搜索的title，并更新session中的search_game_id
			session('search_new_title',$title);  //把查询的新闻title保存到session中; 
		}
		
		//如果既没有传页码，也没有传游戏的ID，则清空保存的appid
		if(!$page && !$title){
			session('search_new_title',null);  //清空保存的游戏ID; 				
		}

		//获取符合条件的总条数
		$webArticle = M('webArticle');
		$total = $webArticle->where($map)->count();
		
		//获取分页类
		$page = $this->page($total,$rows);
		
		//获取新闻信息
		$news = $webArticle->where($map)->order('id DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
		$this->assign('news',$news);
		$this->assign("page", $page->show('Admin'));
	}
		
	//显示添加新闻页面
	public function addNews(){
		$this->_game(true, 2, 2 );
		$this -> display();
	}

	/**
	 * 添加新闻
	 */
	public function addNews_post(){
		//获取数据
		$news_data['app_id'] = I('app_id');  //游戏的ID
		$news_data['title'] = I('title');	 //新闻或活动的标题  
		$news_data['is_top'] = I('is_top');  //是否置顶   1、置顶 2、正常 默认正常
		$news_data['type'] = I('type');      //类型：1为新闻公告，2为活动公告,3为游戏资料，4为攻略中心，3，4暂时还没有用到 默认新闻
		$content_data['content'] = htmlspecialchars_decode(I("post.content"));; //新闻或活动的内容
		$news_data['create_time'] = time();  //创建时间

		if(empty($news_data['app_id']) || empty($news_data['title']) || empty($content_data['content'])){
			$this->error("请填写完数据后再提交");
			exit;
		}
		
		//插入数据
		$article = M("webArticle");
		$news_data['is_delete'] = 2;
		if($article->create($news_data)){ 
			$lastInsId = $article->add();
			//添加新闻内容
			$content = M('webContent');
			$dataList = array('id'=>$lastInsId,'content'=>$content_data['content']);
			$content->add($dataList);
			$this->success("添加成功!", U("News/findList"));
			exit;
		} else { 
			$this->error("添加失败");
			exit;
		}
	}
	
	//删除新闻或活动
	public function delNews(){
		$id = I("id");
		$model = M("webArticle");
		$date["is_delete"] = '1';
		$rs = $model->where("id = '%s'",$id)->data($date)->save();
		if ($rs){
			$this->success("删除成功。", U("News/findList"));
		}
		$this->error("删除失败.");
	}
	
	//编辑新闻或活动
	public function editNews(){
		//接收新闻或活动的ID
		$id = I("id/d");
		
		//获取新闻或活动的信息
		$model = M("webArticle");
		$artdate = $model->where("id = '%d'",$id)->find();
		
		//获取新闻或活动的具体内容
		$content = M("webContent");
		$condate = $content->where("id = '%d'",$id)->find();
		
		//获取所有已上线且没有被删除的游戏名
		$this->_game(true,2,2);
		$this->assign("artdata",$artdate);
		$this->assign("condata",$condate);
		$this->display();
	}
	
	//编辑新闻的信息
	public function editNews_post(){
		//获取数据
		$id = I("id");
		$news_data['app_id'] = I('app_id');					//游戏的ID
		$news_data['title'] = I('title');                   //新闻或活动的标题  
		$news_data['is_top'] = I('is_top');                 //是否置顶   1、置顶 2、正常 默认正常
		$news_data['type'] = I('type');                     //类型：1为新闻公告，2为活动公告,3为游戏资料，4为攻略中心，3，4暂时还没有用到 默认新闻
		$content_data['content'] = htmlspecialchars_decode(I("content"));
		$news_data['create_time'] = time();                 //创建时间

		//插入数据
		$article = M("webArticle");
		$rs = $article->where("id = '%s'",$id)->data($news_data)->save();
		
		$content = M("webContent");
		$rs1 = $content->where("id = '%s'",$id)->data($content_data)->save();
		if (false != $rs && false != $rs1){
		    $this->success("修改成功.", U("News/findList"));
		    exit;
		}		
		
		$this->error("修改失败.");
	}
}