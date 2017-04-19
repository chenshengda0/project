<?php

/*
 *客服中心
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;

class ServerController extends HomebaseController {

	public function index(){
		
		$item = I('item');
		$logo = getGuanggao(4);
		$this->assign("WEB_ICP", C('WEB_ICP'));
		$this->assign("logo",$logo);
    	$this->assign(WEBSITE,__ROOT__.'/public');
    	
    	//获取客服联系信息
		$contact_data = getcontact();
		$hotline = $contact_data['tel'];
		$qq = $contact_data['qq'];
		$email = $contact_data['email'];

		$this->assign("hotline",$hotline);
		$this->assign("email",$email);
		$this->assign("qq", $qq);
		//热门游戏列表清单
		$hotgamelist = hotgamelist();
		$this -> assign("footgamelist", $hotgamelist);

		if(I('item') != ''){
			if($item == 'zhongxin'){
				$this -> display();
				exit;
			}else if($item == 'question'){
				$this -> display('kefu_question');
				exit;
			}else if($item == 'findpwd'){
				$this -> display('kefu_findpwd');
				exit;
			}else if($item == 'modifypwd'){
				$this -> display('kefu_modify_pwd');
				exit;
			}else if($item == 'secretpwd'){
				$this -> display('kefu_secretpwd');
				exit;
			}else if($item == 'tiwen'){
				if(session('user.sdkuser') == ''){
					echo "<script>alert('你太久没操作,已经退出登录')</script>";
					echo "<meta http-equiv=refresh content=0;URL=".U('Login/index').">";
					exit;
				}else{
					$gamelist = gamelist();
					$this->assign('gamelist',$gamelist);
					$this -> display('kefu_tiwen');
					exit;
				}
			}
		}else if(I('post.action')){
			
			if(I('action') && I('action') == 'myask'){
				$status = 0;
				$reg_time = time();
				$update_time = time();
				$username = session("user.sdkuser");
				$uid = checkusername($username);
				$gameid = $_POST['game'];
				$title = isset($_POST['title']) ? $_POST['title'] : '';
				$details = isset($_POST['details']) ? $_POST['details'] : '';
				$contact = isset($_POST['content']) ? $_POST['content'] : '';

				$arrType=array('image/jpg','image/gif','image/png','image/bmp','image/pjpeg','image/jpeg');
				$max_size='51200';      // 最大文件限制（单位：byte）
				$upfile = C('UPLOADPATH')."ask/"; //图片目录路径
				$file=$_FILES['screenshot'];
				if(is_uploaded_file($file['tmp_name'])){
					if($_SERVER['REQUEST_METHOD'] == 'POST'){ //判断提交方式是否为POST
			
						if(!is_uploaded_file($file['tmp_name'])){ //判断上传文件是否存在
							echo "<script>alert('文件不存在！')</script>";
							echo "<meta http-equiv=refresh content=0;URL=".U('Server/index/item/tiwen').">";
							exit();
						}
				   
						if($file['size']>$max_size){  //判断文件大小是否大于500000字节
							echo "<script>alert('上传文件太大！')</script>";
							echo "<meta http-equiv=refresh content=0;URL=".U('Server/index/item/tiwen').">";
							exit();
						 } 
			
						if(!in_array($file['type'],$arrType)){  //判断图片文件的格式
							echo "<script>alert('上传文件格式不对！')</script>";
							echo "<meta http-equiv=refresh content=0;URL=".U('Server/index/item/tiwen').">";
							exit();
						 }
						if(!file_exists($upfile)){  // 判断存放文件目录是否存在
							 mkdir($upfile,0777,true);
						 } 
						 $imageSize = getimagesize($file['tmp_name']);
						 $img = $imageSize[0].'*'.$imageSize[1];
						 $fname = $file['name'];
						 $ftypearr = explode('.',$fname);
						 $ftype = $ftypearr[1];
			
						 $fname = time().'.'.$ftype;
						 $picName = $upfile.$fname;
						 $image = $fname;
						 
						if(file_exists($picName)){
							echo "<script>alert('同文件名已存在！')</script>";
							echo "<meta http-equiv=refresh content=0;URL=".U('Server/index/item/tiwen').">";
							exit();
						}
						
						if(!move_uploaded_file($file['tmp_name'],$picName)){  
							echo "<script>alert('移动文件出错')</script>";
							echo "<meta http-equiv=refresh content=0;URL=".U('Server/index/item/tiwen').">";
							exit();
							
						}
					}
				}

			    $rs = insertask($gameid,$title,$details,$uid,$contact,$image,$reg_time,$status);
			  
			    if($rs){
					echo "<script>alert('问题提交成功，非常感谢您的支持')</script>";
					echo "<meta http-equiv=refresh content=0;URL=".U('Server/index/item/zhongxin').">";
					exit();
				}else{
					unlink($upfile.$image);
					echo "<script>alert('提交失败，请重新填写');history.go(-1);</script>";
					exit();
				}
			}
		}
    }
}