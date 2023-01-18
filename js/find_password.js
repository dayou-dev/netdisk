var RegType=0;
var rtimers=60;
var smsCodediv="#sendCode";

// 定时
function rtimer_(){
	if (rtimers >= 0) {
		$("#send_txt").html('<em>'+rtimers+'s</em>后重新发送');
		rtimers--;
	} else {
		window.clearInterval(rtipId);
		$("#send_bnt").attr("onclick", "getcode();");
		$("#send_txt").html("重新发送验证").css("color","#1b2539");
		$("#send_bnt").css('background','#fff');
	}
}

function getRegCheckCode(){
	$("#send_txt").html('链接发送中').css("color","#999");
	$("#send_bnt").css('background','#ddd');
	$("#send_bnt").removeAttr('onclick'); // 去掉a标签中的onclick事件;
	
	var email=$("#email").val();
	if(checkEmail(email)){
		layer.msg("邮箱格式不正确，请重新输入");
		return;	
	};
	
	$.post("/index/send_reset_email",{email:email,SessionId:$("#SessionId").val(),Token:$("#Token").val(),Sig:$("#Sig").val()},function(data){
		if(data.status==1){
			$("#send_txt").html('<em>60s</em>后重新发送');
			rtimers = 60;
			rtipId = window.setInterval(rtimer_,1000);
		}else{
			 //smsCodediv.innerHTML = "重新发送验证码";
			  $("#send_bnt").attr("onclick", "getcode();");
			  $("#send_txt").html("重新发送验证").css("color","#1b2539");
			  $("#send_bnt").css('background','#fff');
			  layer.msg(data.info);
			  //relint=1;
			  //ic_rel();
		}
	},"json");
}

function checkPass(sv) {
	if (sv == '') {
		return true;
	} else if ((!/^(\w){6,20}$/.test(sv))) {
		return true;
	}
	return false;
}	

function checkEmail(sv) {
	if (sv == '') {
		return true;
	} else if ((!/^[A-Za-zd0-9]+([-_.][A-Za-zd]+)*@([A-Za-zd0-9]+[-.])+[A-Za-zd0-9]{2,5}$/.test(sv))) {
		return true;
	}
	return false;
}	


function send_link(){
	$("#vchk").html('');
	$("#Token").val('');
	$("#smschk-box").show();
	var v = $("#email");
    var ic = new smartCaptcha({
        renderTo: '#vchk',
        width: 300,
        height: 42,
        default_txt: '点击按钮开始智能验证',
        success_txt: '验证成功',
        fail_txt: '验证失败，请在此点击按钮刷新',
        scaning_txt: '智能检测中',
        success: function(data) {
         // console.log(NVC_Opt.token)
         // console.log(data.sessionId);
         // console.log(data.sig);
			 document.getElementById("SessionId").value=data.sessionId;
			 document.getElementById("Token").value=NVC_Opt.token;
			 document.getElementById("Sig").value=data.sig;
		 	 //aliCaptcha();
			 $("#smschk-box").hide();
			 getRegCheckCode();
        },
        fail: function(data) {
         console.log('ic error');
        }
    });	
	ic.init();
}

function getcode() {
	var v = $("#email");
	var temp = true;
	if ($(v).val()!="") {
		if(checkEmail($(v).val())){
			layer.msg("邮箱地址格式不正确，请重新输入");
			return;	
		}
		$.ajax({
			url: "/index/account_exist/",
			data: { email: $(v).val() },
			dataType: "json",
			type: "get",
			async:false,
			error: function() {},
			success: function(data) {
				if (!data.status) {
					layer.msg('Email未注册，请重新输入');
				}else{
					send_link();
				}
			}

		});
	}else{
		layer.msg("请输您的Email地址");
		return;	
	}
	return temp;
};


	var formint=0;
	function fromsubmit(s){
		if (formint>0) {
			return;
		}
		
		if(s==0){
			$("#accinfoChk").show();
			$("#passInput").hide();
			if (RegType<1) {          
			   if(checkPhone($("#phone").val())){
				   layer.msg("请输入手机号码");
				   return;
				}
			}else{
			   if(checkEmail($("#email").val())){
				   layer.msg("请输入正确的Email地址");
				   return;
				}
			}
		
			if($("#verificationcode").val()==''){
				layer.msg("请输入"+(RegType<1?"手机":"邮箱")+"验证码");
				return;
			}
			
			var btn=$("#chkbnt1");
			btn.addClass("b_btnDisable");
			formint=1;
			$.ajax({
				  url: "/user/find_password_sms",
				  data: {
					  regtype: RegType,
					  email: $("#email").val(),
					  phone: $("#phone").val(),
					  password: $("#password").val(),
					  mobilecode: $("#verificationcode").val(),
					  commuser: $("#invite_code").val()
				  },
				  dataType: "json",
				  type: "post",
				  error: function(xhr, ajaxOptions, thrownError) {
					  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  },
				  beforeSend: function() {
						  //$('.loadinfo_div').show();
				  },
				  complete: function() {
						  //$('.loadinfo_div').hide();
						  formint=0;
				  },
				  success: function(data) {
					  if (data.status) {
						$("#accinfoChk").hide();
						$("#passInput").show();		  
					  }else{
						  layer.msg(data.info);
						  //ic_rel();
						  //nc.reload();
					  }
					  btn.removeClass("b_btnDisable");
				  }
			});
		}else{
			if(checkPass($("#password").val())){
				layer.msg("密码为6-20个字母、数字、下划线组成");
				return;
			}
			
			if($("#compare_pwd").val()!=$("#password").val()){
				layer.msg("密码不一致，请重新输入");
				return;
			}
			
			var btn=$("#chkbnt2");
			btn.addClass("b_btnDisable");
			formint=1;
			$.ajax({
				  url: "/user/reset_password",
				  data: {
					  password: $("#password").val()
				  },
				  dataType: "json",
				  type: "post",
				  error: function(xhr, ajaxOptions, thrownError) {
					  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  },
				  beforeSend: function() {
						  //$('.loadinfo_div').show();
				  },
				  complete: function() {
						  //$('.loadinfo_div').hide();
						  formint=0;
				  },
				  success: function(data) {
					  if (data.status) {
						  //layer.msg('注册成功');
						  location.href="/user/reset_password_finish"
					  }else{
						  layer.msg(data.info);
					  }
					  btn.removeClass("b_btnDisable");
				  }
			});
		}
	}


