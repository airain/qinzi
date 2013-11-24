<?php
/**
 * classname: RulesModel
 * des: 积分规则类
 */
class RulesModel extends BaseModel{
	
	public $_table = 'qinzi_rules';
	private $_primaryKey = 'id';
	/**
	 * fun: modRuleInfo
	 * des: 修改积分规则
	 * 
	 * @param int $id id
	 * @param array $info 修改信息[field->value]
	 * @return boolean|int A
	 */
	public function modRuleInfo($id, array $info = array()){
		if($uid <= 0) return false;
		$where = $this->_primaryKey . '='. $id;

		return $this->update($info, $where);
	}//modbabyinfo

	/**
	 * fun: addRule
	 * des: 添加积分规则
	 * 
	 * @param array $info 添加积分规则[field->value]
	 * @return boolean|int A
	 */
	public function addRule(array $info = array()){

		return $this->add($info);
	}//addUser
	
}

/**
 * className: RuleRecordModel
 * des: 积分记录类
 */
class RuleRecordModel extends BaseModel {
	public $_table = 'qinzi_rule_record';
	private $_primaryKey = 'id';

	/**
	 * fun: addJifen
	 * des: 添加积分
	 *
	 * @param int $uid
	 * @param string $rule_name
	 * @param array $info [info,pre_score]
	 * @return boolean|int A
	 */
	public function addJifen($uid, $rule_name, array $info = array()){
		if(empty($uid) || empty($rule_name)) false;
		$ruleObj = new RulesModel();
		$ruleInfo = $ruleObj->getOne('ename="'.$rule_name.'"');
		if(!$ruleInfo) return false;
		$curtime = time();
		//时间间隔内大于规定的次数
		if($ruleInfo['limit_count'] && $ruleInfo['limit_time']){
			$tmp_count = $this->getCount('uid='.$uid.' AND ename="'.$rule_name.'" AND createtime >= '.($curtime - $ruleInfo['limit_time']));
			if($tmp_count >= $ruleInfo['limit_count']){
				return false;
			}
		}

		$info['uid'] = $uid;
		$info['score'] = (int)($ruleInfo['type'] . $ruleInfo['score']);
		$info['ename'] = $rule_name;
		$info['createtime'] = time();
		return $this->add($info);
	}//addJifen

}
