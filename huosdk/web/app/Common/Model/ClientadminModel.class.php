<?php
namespace Common\Model;
use Common\Model\CommonModel;
class ClientadminModel extends CommonModel{
	
	protected $trueTableName = 'db_sdk_mn.l_clientadmin'; 
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
}