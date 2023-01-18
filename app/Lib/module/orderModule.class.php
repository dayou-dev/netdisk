<?php
class orderModule extends SiteBaseModule
{
	public function index()
	{
		
		$s1=intval($_REQUEST['s1']);
		$s2=intval($_REQUEST['s2']);
		$s3=intval($_REQUEST['s3']);
		$is5g=intval($_REQUEST['is_5g']);
		$number=htmlstrchk(trim($_REQUEST['number']));
		$chkattr1 = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."product_attr where id=$s1");
		$chkattr2 = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."product_attrlist where id=$s2");
		$proName = $chkattr2['title'];
		if(!$chkattr1||!$chkattr2){
			header("Location:/");
			exit();
		}
		$proInfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."product where id=".$chkattr1['pid']);
		$GLOBALS['tmpl']->assign("proInfo",$proInfo);
		
		if($proInfo['id']=='25'){
			$reUrl='/';
		}else{
			$reUrl=$proInfo['seo_url']?'/'.$proInfo['seo_url'].'.shtml':'/index/'.$proInfo['id'];
		}
		$GLOBALS['tmpl']->assign("reUrl",$reUrl);
		if($_POST['ajax']){
			
			
			$province_id=intval($_POST['province_id']);
			$city_id=intval($_POST['city_id']);
			$region_id=intval($_POST['region_id']);
			$address=htmlstrchk($_POST['address']);
			
			$phone=htmlstrchk($_POST['phone']);
			$mobilecode = trim($_POST['mobilecode']);
			$certName=htmlstrchk($_POST['certName']);
			$certNo=htmlstrchk($_POST['certNo']);
			
			$sendcode=es_session::get('mobilecode');
			$sendmobile=es_session::get('sendmobile');
			if(!$certName||!$certName)
			{				
				$return['info']=='姓名/身份证不能为空';
				$return['status']=0;
				ajax_return($return);
			}			
			
			if(!$phone)
			{				
				$return['info']=='请输入手机号码';
				$return['status']=0;
				ajax_return($return);
			}			
			
			if(!$mobilecode)
			{				
				$return['info']='请输入短信验证码';
				$return['status']=0;
				ajax_return($return);
			}			
			
			if($mobilecode!=$sendcode||$phone!=$sendmobile)
			{				
				$return['info']='手机验证码错误';
				$return['status']=0;
				ajax_return($return);
			}	
			
			$userdata=array();
			$userdata['ordernu'] = time();
			$userdata['names'] = $certName;
			$userdata['idno'] = $certNo;
			$userdata['telephone'] = $phone;
			$userdata['province_id'] = $province_id;
			$userdata['city_id'] = $city_id;
			$userdata['region_id'] = $region_id;
			$userdata['address_1'] = $address;
			$userdata['attr1'] = $chkattr1['title'];
			$userdata['attr2'] = $chkattr2['title'];
			$userdata['attr3'] = $s3;
			$userdata['price'] = $chkattr2['price'];
			$userdata['is_5g'] = $chkattr1['is_5g'];
			$userdata['pid'] = $chkattr1['pid'];
			$userdata['ip'] = get_client_ip();
			$userdata['update_time'] = time();
			$userdata['create_time'] = time();
			$GLOBALS['db']->autoExecute(DB_PREFIX."order",$userdata); //插入
			$reid = $GLOBALS['db']->insert_id();
			
			$goodsId="";
			$firstMonthFee="";			
			if($proInfo['proType']=='1'){
				//菲菲
				$goodsId="511912030113";
			}else if($proInfo['proType']=='2'){
				//阿里宝卡
				if(intval($chkattr2['price'])==19) $goodsId="981711282733";
				if(intval($chkattr2['price'])==59) $goodsId="981711282734";
			}else{
				 //公共版
				 if($chkattr1['is_5g']){
					if(intval($chkattr2['price'])==129) $goodsId="511911068977";
					if(intval($chkattr2['price'])==199) $goodsId="511911068979";
					if(intval($chkattr2['price'])==299) $goodsId="511911068980";
					$firstMonthFee = $s3 ? "A000011V000002" : "A000011V000001";
				 }else{
					if(intval($chkattr2['price'])==19) $goodsId="981610241535";
					if(intval($chkattr2['price'])==39) $goodsId="981802085690";
					if(intval($chkattr2['price'])==59) $goodsId="981702278573";
				 }
			}
			$essProvince=$GLOBALS['db']->getOne("select cityid from ".DB_PREFIX."region where id=$province_id");
			$essCity=$GLOBALS['db']->getOne("select cityid from ".DB_PREFIX."region where id=$city_id");
			$postdata=array();
			$postdata['certId']=$certNo;
			$postdata['certName']=$certName;
			$postdata['contractPhone']=$phone;
			$postdata['number']=$number;
			$postdata['essProvince']=$essProvince;
			$postdata['essCity']=$essCity;
			$postdata['address']=$address;
			$postdata['goodsId']=$goodsId;
			$postdata['firstMonthFee']=$firstMonthFee;
			$postdata['webProvince']=$GLOBALS['db']->getOne("SELECT provincode from ".DB_PREFIX."region where id=$province_id");
			$postdata['webCity']=$GLOBALS['db']->getOne("SELECT citycode from ".DB_PREFIX."region where id=$city_id");
			$postdata['webCounty']=$GLOBALS['db']->getOne("SELECT citycode from ".DB_PREFIX."region where id=$region_id");
			
			$udata=array();
			if($reid){
				$result['status'] = 1;
				$result['info'] = "";
				if($proInfo['proType']!='1'){
					 if($chkattr1['is_5g']){
						 $udata=$this->getdata3($postdata);	
					 }else{
						 $udata=$this->getdata2($postdata);	
					 }
				}else{
					 $udata=$this->getdata1($postdata);
				}
				//if($chkattr1['pid']=='28') $udata=$this->getdata1($postdata);
				if($udata){
					if($udata['orderId']){
						$GLOBALS['db']->query("update ".DB_PREFIX."order set traceId='".$udata['traceId']."',orderId='".$udata['orderId']."',orderNo='".$udata['orderNo']."',number='".$udata['number']."',rspDesc='".$udata['rspDesc']."' where id=$reid");
						es_session::delete("mobilecode");
						es_session::delete("sendmobile");
					}else{
						$GLOBALS['db']->query("delete from ".DB_PREFIX."order where id=$reid");
						$return['info']=$udata['rspDesc'];
						$return['status']=0;
						ajax_return($return);
					}
				}
			}
			ajax_return($result);
		}
		if($proInfo['proType']=='1') $wr=" and id=3822";
		
		$region = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region where region_level = 0 $wr order by id asc");
		$GLOBALS['tmpl']->assign("region",$region);
		$GLOBALS['tmpl']->assign('s1',$s1);	
		$GLOBALS['tmpl']->assign('s2',$s2);	
		$GLOBALS['tmpl']->assign('s3',$s3);	
		$GLOBALS['tmpl']->assign('is5g',$is5g);	
		$GLOBALS['tmpl']->assign('proName',$proName);	
		$GLOBALS['tmpl']->display("order.html");
	}
	
	//菲菲
	private function getdata1($data=array()){
		//获取号码
		$sdata['provinceCode']="51";
		$sdata['cityCode']="540";
		$sdata['goodsId']=$data['goodsId'];
		$number = $data['number']?$data['number']:$this->get_telnu($sdata);
		//$number = "15625290154";
		$url = "https://msgo.10010.com/order-server/scene/buy";
		$redata=array();
		$redata["certInfo"]= array("certId"=>$data['certId'],"certName"=>$data['certName'],"contractPhone"=>$data['contractPhone']);
		$redata["contractSelectFlag"]="0";
		$redata["goodInfo"]= array("goodsId"=>$data['goodsId'],"sceneFlag"=>"03","goodsPrice"=>"0");
		$redata["marketingStatus"]="0";
		$redata["numInfo"]= array("essCity"=>"540","essProvince"=>"51","number"=>$number);
		$redata["postInfo"]= array("address"=>$data['address'],"webCity"=>$data['webCity'],"webCounty"=>$data['webCounty'],"webProvince"=>$data['webProvince']);
		$attrdata1[0]=array("optProductId"=>"","optProductName"=>"","optProductPrice"=>"0");
		$redata["productInfo"]= array("firstMonthFee"=>"A000011V000001","optFlag"=>"0","optProductInfos"=>$attrdata1,"salesId"=>"X1912031704596546");
		$attrdata2[0]=array("protocolId"=>19105594,"protocolType"=>"00");
		$attrdata2[0]=array("protocolId"=>19105521,"protocolType"=>"01");
		$redata["protocolReqBeans"]= $attrdata2;
		$redata["u"]="LPMOObqKhnHUoZC5Fka1SQ==";
		//echo print_r($redata);exit();
		$data = json_encode($redata);
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSLVERSION, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		$content = curl_exec($curl);
		$redata = json_decode($content);
		$return['number'] = $number;
		$return['traceId'] = $redata->traceId;
		$return['orderId'] = $redata->orderId;
		$return['orderNo'] = $redata->orderNo;
		$return['rspDesc'] = $redata->rspDesc;
		curl_close($curl);
		return $return;
	}
	
	//非5g
	private function getdata2($data=array()){
		
		
		if($data['essProvince']=='50') $data['essCity']="501"; //海南
		if($data['essProvince']=='51') $data['essCity']="540"; //广东
		
		//获取号码
		$sdata['provinceCode']=$data['essProvince'];
		$sdata['cityCode']=$data['essCity'];
		$sdata['goodsId']=$data['goodsId'];
		
		
		
		$number = $data['number']?$data['number']:$this->get_telnu($sdata);
		
		$url = "https://msgo.10010.com/scene-buy/scene/buy"; //全部城区
		$redata=array();
		$redata["certInfo"]= array("certId"=>$data['certId'],"certName"=>$data['certName'],"certTypeCode"=>"02","contractPhone"=>$data['contractPhone']);
		$redata["channel"]="9999";
		$redata["goodInfo"]= array("goodsId"=>$data['goodsId'],"sceneFlag"=>"03");
		$redata["marketingStatus"]="0";
		$redata["numInfo"]= array("essCity"=>$data['essCity'],"essProvince"=>$data['essProvince'],"number"=>$number);
		$redata["postInfo"]= array("address"=>$data['address'],"webCity"=>$data['webCity'],"webCounty"=>$data['webCounty'],"webProvince"=>$data['webProvince']);
		$redata["u"]="XPed7%20mQDAK4Plo18y%20vvQ==";
		if($sdata['goodsId']=='981711282733'||$sdata['goodsId']=='981711282734'){
			$redata["u"]="LPMOObqKhnHUoZC5Fka1SQ=="	;
		}
		
		$data = json_encode($redata);
		//echo print_r($redata);exit();
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSLVERSION, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		$content = curl_exec($curl);
		curl_close($curl);
		$redata = json_decode($content);
		//echo print_r($redata);
		$return['number'] = $number;
		$return['traceId'] = $redata->traceId;
		$return['orderId'] = $redata->orderId;
		$return['orderNo'] = $redata->orderNo;
		$return['rspDesc'] = $redata->rspDesc;
		curl_close($curl);
		return $return;
	}
	
	//5g
	private function getdata3($data=array()){
		$url = "https://msgo.10010.com/order-server/scene/buy"; //5G
		
		if($data['essProvince']=='50') $data['essCity']="501"; //海南
		if($data['essProvince']=='51') $data['essCity']="540"; //广东
		
		//获取号码
		$data['essProvince']="51";
		$sdata['provinceCode']=$data['essProvince'];
		$sdata['cityCode']=$data['essCity'];
		$sdata['goodsId']=$data['goodsId'];
		$number = $data['number']?$data['number']:$this->get_telnu($sdata);
		
		$redata=array();
		$attrdata[0]=array("optProductId"=>"","optProductName"=>"","optProductPrice"=>"0");
		$attrdata2[0]=array("protocolId"=>"19126147","protocolType"=>"00");
		
		$redata["certInfo"]= array("certId"=>$data['certId'],"certName"=>$data['certName'],"contractPhone"=>$data['contractPhone']);
		$redata["contractSelectFlag"]="0";
		$redata["goodInfo"]= array("goodsId"=>$data['goodsId'],"goodsPrice"=>"0","sceneFlag"=>"03");
		$redata["marketingStatus"]="0";
		$redata["numInfo"]= array("essCity"=>$data['essCity'],"essProvince"=>$data['essProvince'],"number"=>$number);
		$redata["postInfo"]= array("address"=>$data['address'],"webCity"=>$data['webCity'],"webCounty"=>$data['webCounty'],"webProvince"=>$data['webProvince']);
		
		$redata["productInfo"]= array("firstMonthFee"=>$data['firstMonthFee'],"optFlag"=>"0","optProductInfos"=>$attrdata,"salesId"=>"X1911061004568747");
		$redata["protocolReqBeans"]= $attrdata2;
		$redata["u"]="XPed7+mQDAK4Plo18y+vvQ==";
		
		$data = json_encode($redata);
		//echo print_r($redata);exit();
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSLVERSION, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		$content = curl_exec($curl);
		curl_close($curl);
		$redata = json_decode($content);
		
		$return['number'] = $number;
		$return['traceId'] = $redata->traceId;
		$return['orderId'] = $redata->orderId;
		$return['orderNo'] = $redata->orderNo;
		$return['rspDesc'] = $redata->rspDesc;
		curl_close($curl);
		return $return;
	}
	
	private function get_telnu($data=array()){
		
		if($data['provinceCode']=='50') $data['cityCode']="501"; //海南
		if($data['provinceCode']=='51') $data['cityCode']="540"; //广东
		
		$url="https://msgo.10010.com/NumApp/NumberCenter/qryNum?callback=jsonp_queryMoreNums&provinceCode=".$data['provinceCode']."&cityCode=".$data['cityCode']."&monthFeeLimit=0&goodsId=".$data['goodsId']."&searchCategory=3&net=01&amounts=200&codeTypeCode=&searchValue=&qryType=02&goodsNet=4&channel=msg-xsg&_=".time();
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array());
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSLVERSION, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		$content = curl_exec($curl);
		curl_close($curl);
		$content = str_replace("jsonp_queryMoreNums(","",$content );
		$content = str_replace(")","",$content );
		$redata = json_decode($content);
		curl_close($curl);
		return $redata->numArray[0];
		
	}
	
}	
?>