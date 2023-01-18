var RegType=0;
var rtimers=60;
var smsCodediv="#sendCode";

// 定时
function rtimer_(){
	if (rtimers >= 0) {
		$(smsCodediv).html('<em>'+rtimers+'s</em>后重新发送');
		rtimers--;
	} else {
		window.clearInterval(rtipId);
		$(smsCodediv).attr("onclick", "getcode();");
		$(smsCodediv).html("重新发送验证").css("color","#00BC7E");
	}
}

function getRegCheckCode(){
	$(smsCodediv).html('验证码获取中').css("color","#999");
	$(smsCodediv).removeAttr('onclick'); // 去掉a标签中的onclick事件;
	
	var email=$("#email").val();
	if(checkEmail(email)){
		layer.msg("邮箱格式不正确，请重新输入");
		return;	
	};
	
	$.post("/index/getRegisterCode",{e:email,SessionId:$("#SessionId").val(),Token:$("#Token").val(),Sig:$("#Sig").val()},function(data){
		if(data.status==1){
			$(smsCodediv).html('<em>60s</em>后重新发送');
			rtimers = 60;
			rtipId = window.setInterval(rtimer_,1000);
		}else{
			 //smsCodediv.innerHTML = "重新发送验证码";
			  $(smsCodediv).attr("onclick", "getcode();");
			  $(smsCodediv).html("重新发送验证").css("color","#00BC7E");
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
					send_link();
				}else{
					layer.msg('当前Email已注册，请重新输入');
				}
			}

		});
	}else{
		layer.msg("请输您的Email地址");
		return;	
	}
	return temp;
};


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



/*$("#email").val('334047053@qq.com');
$("#verificationcode").val(111111);
$("#password").val(111111);
$("#compare_pwd").val(111111);
regsubmit();*/

	var formint=0;
	function regsubmit(){
		if (formint>0) {
			return;
		}
		   if(checkEmail($("#email").val())){
			   layer.msg("请输入正确的Email地址");
			   return;
			}
		
		if($("#verificationcode").val()==''){
			layer.msg("请输入邮箱验证码");
			return;
		}
		
		if(checkPass($("#password").val())){
			layer.msg("密码为6-20个字母、数字、下划线组成");
			return;
		}
		
		if($("#compare_pwd").val()!=$("#password").val()){
			layer.msg("密码不一致，请重新输入");
			return;
		}
 		var btn=$("#regbnt");
		btn.addClass("b_btnDisable");
		formint=1;
		$.ajax({
				url: "/index/register",
				data: {
					email: $("#email").val(),
					password: $("#password").val(),
					number: $("#verificationcode").val()
				},
				dataType: "json",
				//contentType: 'application/json',
				//crossDomain: true,
				type: "post",
				error: function(xhr, ajaxOptions, thrownError) {
					//console.log(xhr.responseText);
					var redata=JSON.parse(xhr.responseText);
					layer.msg(redata['error']['message'])
					//xhr.responseText			
// 					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
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
						layer.msg('注册成功');
						setTimeout("loadpage()",1500);
						//location.href="/user/register_success"
					}else{
						layer.msg(data.info);
					}
					btn.removeClass("b_btnDisable");
				}
        });



		
	}
	
function loadpage(){
	location.href='/login.html'
}

