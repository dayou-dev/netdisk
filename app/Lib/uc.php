<?php
	$user_info = es_session::get("user_info");
	if($user_info)
	{
		$ajax=$_REQUEST['ajax'];
		$user_id = intval($user_info['id']);
		$c_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($user_id));
		if(!$c_user_info){
			es_session::set("user_info",'');
			$GLOBALS['tmpl']->assign("bntTxt","立即登录");
			showErr("请登录后在进行操作",$ajax,"/login.html");
		}else{
			$GLOBALS['tmpl']->assign("user_info",$c_user_info);
		}
	}
	else
	{
		$GLOBALS['tmpl']->assign("bntTxt","立即登录");
		showErr("请登录后在进行操作",$ajax,"/login.html");
	}

	
    function parseWhere($tab,$where,$sort) {
        $whereStr = '';
		$comparison  = array('eq'=>'=','neq'=>'!=','gt'=>'>','egt'=>'>=','lt'=>'<','elt'=>'<=','notlike'=>'NOT LIKE','like'=>'LIKE');
		if($where){
			$operate    =   ' AND ';
            $kk=0;
			foreach ($where as $key=>$val){
                $whereStr .= "( ";
				if(is_array($val)) {
					if(is_string($val[0])) {
						if(preg_match('/^(EQ|NEQ|GT|EGT|LT|ELT|NOTLIKE|LIKE)$/i',$val[0])) { // 比较运算
							$whereStr .= '`'.$key.'` '.$comparison[strtolower($val[0])].' '.parseValue($val[1]);
						}elseif('exp'==strtolower($val[0])){ // 使用表达式
							$whereStr .= ' (`'.$key.'` '.$val[1].') ';
						}elseif(preg_match('/IN/i',$val[0])){ // IN 运算
							if(is_array($val[1])) {
								array_walk($val[1], array($this, 'parseValue'));
								$zone   =   implode(',',$val[1]);
							}else{
								$zone   =   $val[1];
							}
							$whereStr .= '`'.$key.'` '.strtoupper($val[0]).' ('.$zone.')';
						}elseif(preg_match('/BETWEEN/i',$val[0])){ // BETWEEN运算
							$data = is_string($val[1])? explode(',',$val[1]):$val[1];
							$whereStr .=  ' (`'.$key.'` '.strtoupper($val[0]).' '.parseValue($data[0]).' AND '.parseValue($data[1]).' )';
						}else{
							
						}
					}else {
						$count = count($val);
						if(in_array(strtoupper(trim($val[$count-1])),array('AND','OR','XOR'))) {
							$rule = strtoupper(trim($val[$count-1]));
							$count   =  $count -1;
						}else{
							$rule = 'AND';
						}
						for($i=0;$i<$count;$i++) {
							$data = is_array($val[$i])?$val[$i][1]:$val[$i];
							if('exp'==strtolower($val[$i][0])) {
								$whereStr .= '(`'.$key.'` '.$data.') '.$rule.' ';
							}else{
								$op = is_array($val[$i])?$comparison[strtolower($val[$i][0])]:'=';
								$whereStr .= '(`'.$key.'` '.$op.' '.parseValue($data).') '.$rule.' ';
							}
						}
						$whereStr = substr($whereStr,0,-4);
					}
                }else {
					$whereStr .= '`'.$key."` = ".parseValue($val);
                }
				if($kk==count($where)-1){
					$whereStr .= ' )';
				}else{
					$whereStr .= ' )'.$operate;
				}
				$kk++;
			}
		}
		$wr_str=(empty($whereStr)?'':' WHERE '.$whereStr);
		

		$tsql="select * from ".$tab.$wr_str;
		if($sort) $tsql.=" order by ".$sort;
        return $tsql;
    }

    function parseValue(&$value) {
        if(is_string($value)) {
            $value = '\''.escape_string($value).'\'';
        }elseif(isset($value[0]) && is_string($value[0]) && strtolower($value[0]) == 'exp'){
            $value   =  escape_string($value[1]);
        }elseif(is_null($value)){
            $value   =  'null';
        }
        return $value;
    }
	
	function escape_string($str) {
        return mysql_escape_string($str);
    }
	
	?>