$( document ).ready(function() {
    $('input#phone').keydown(function(event) {
        if (event.keyCode === 13) {
            return true;
        }
        if (!(event.keyCode == 8 || event.keyCode == 46 || (event.keyCode >= 35 && event.keyCode <= 40) || (event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105))) {
            event.preventDefault();
        }
    });

    $('input#cmnd').keydown(function(event) {
        if (event.keyCode === 13) {
            return true;
        }
        if (!(event.keyCode == 8 || event.keyCode == 46 || (event.keyCode >= 35 && event.keyCode <= 40) || (event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105))) {
            event.preventDefault();
        }
    });

    var delay = (function(){
      var timer = 0;
      return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
      };
    })();


    var getname_vcb = function(number,url){

        setTimeout(function(){
            $.ajax({
             url : url,
             type : "POST",
             dateType:"json",
             async : false,
             data : {
                'number_vcb' : number
             },
             success : function (result){
                var json = JSON.parse(result);
                var name = json.account_name;
                if (name == null){
                  getname_vcb(number,url);
                }
                else{
                  if (name=="N/A"){
                    $('#account_holder').attr('placeholder', 'Bank account number does not exist!');
                    $('#account_holder').parent().addClass('has-error');
                    $('#account_number').parent().addClass('has-error');
                    $('.conf-vcb span i').hide();
                  }
                  else
                  {
                    $('#account_holder').val(name);
                    $('label.blue').css({'color':'#468847'});
                    $('input.blue').css({'border':'1px solid #468847'});
                    $('.conf-vcb span i').hide();
                    $('#account_number').parent().addClass('has-success');
                    $('#account_holder').parent().addClass('has-success');
                    $('#account_holder').parent().removeClass('has-error');

                  }
                }
             }
           });
        }, 200)
      };

      $('input#account_number').on("input propertychang", function() {
        $('#account_number').parent().removeClass('has-error');
        $('#account_number').parent().removeClass('has-success');
        if (jQuery('#bank_name').val() == "Vietcombank"){
            $('#account_holder').attr('readonly', true);
            if($(this).val().length === 13){
                $('.conf-vcb span i').show();

               var number = $(this).val();
                    var url  = $(this).data('url') ;
                    getname_vcb(number, url);


            }else{
                $('#account_holder').parent().removeClass('has-success');
                $('.conf-vcb span i').hide();
                $('#account_holder').val('');
            }
        }
    });
    jQuery('#bank_name').change(function(){
        
        if (jQuery('#bank_name').val() == "Vietcombank"){
            $('#account_holder').attr('readonly', true);
            $('#account_holder').val('');
            $('#account_number').val('');
        }
        else
        {
            $('#account_holder').attr('readonly', false);
            $('#account_holder').val('');
            $('#account_number').val('');
        }
    });
    $('#BitcoinWalletAddress').on('keyup',function(){
        delay(function(){
            $.ajax({
                 url : $('#BitcoinWalletAddress').data('link'),
                 type : "GET",
                 dateType:"json",
                 async : false,
                 data : {
                    'wallet' : $('#BitcoinWalletAddress').val()
                 },
                 success : function (result){
                    var json = JSON.parse(result);
                    if (json.wallet == -1)
                    {
                        $('#BitcoinWalletAddress').css({'border':'1px solid red'});
                        $('#BitcoinWalletAddress-error').show();
                        $('#BitcoinWalletAddress-error span').html('Please enter your bitcoin wallet!');
                    }
                    else{
                        $('#BitcoinWalletAddress').css({'border':'1px solid #b2c0c6'});
                        $('#BitcoinWalletAddress-error').hide();
                    }
                 }
            });   
        }, 600);
    });

    $('#register-account').on('submit', function(event) {

        $.fn.existsWithValue = function() {
            return this.length && this.val().length;
        };
        var self = $(this);
        var isValidEmailAddress = function(email, callback) {
            var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
            callback(pattern.test(email));
        };

        var isValidpass = function(email, callback) {
            var mediumRegex = new RegExp("^(?=.{6,})(((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
            callback(mediumRegex.test(email));
        };
        

        var validate = {
            init: function(self) {
                self.find('#username').parent().removeClass('has-error');
                self.find('#user-error').hide();
                 self.find('#BitcoinWalletAddress').parent().removeClass('has-error');
                self.find('#BitcoinWalletAddress-error').hide();
                self.find('#email').parent().removeClass('has-error');
                self.find('#email-error').hide();
                self.find('#phone').parent().removeClass('has-error');
                self.find('#phone-error').hide();
                self.find('#cmnd').parent().removeClass('has-error');
                self.find('#cmnd-error').hide();
                self.find('#country').parent().removeClass('has-error');
                self.find('#country-error').hide();
                self.find('#password').parent().removeClass('has-error');
                self.find('#password-error').hide();
                self.find('#password2').parent().removeClass('has-error');
                self.find('#password2-error').hide();
                self.find('#confirmpassword').parent().removeClass('has-error');
                self.find('#confirmpassword-error').hide();
                self.find('#confirmpasswordtransaction').parent().removeClass('has-error');
                self.find('#confirmpasswordtransaction-error').hide();

                $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');


            },

            userName: function(self) {
                if (self.find('#username').existsWithValue() === 0) {
                    self.find('#username').parent().addClass('has-error');
                    self.find('#user-error').show();
                    self.find('#username').focus();
                    self.find('#user-error span').html('Vui lòng nhập tên đăng nhập');
                    return false;
                }
                return true;
            },
            cmnd: function(self) {
                if (self.find('#cmnd').existsWithValue() === 0) {
                    self.find('#cmnd').parent().addClass('has-error');
                    self.find('#cmnd-error').show();
                    self.find('#cmnd').focus();
                    self.find('#cmnd-error span').html('Vui lòng nhập số chứng minh nhân dân');
                    return false;
                }
                return true;
            },
           /* BitcoinWalletAddress: function(self) {
                if (self.find('#BitcoinWalletAddress').existsWithValue() === 0) {
                    self.find('#BitcoinWalletAddress').parent().addClass('has-error');
                   self.find('#BitcoinWalletAddress-error span').show();
                    self.find('#BitcoinWalletAddress-error span').html('Please enter your bitcoin wallet!');
                    return false;
                }
                return true;
            },*/
            email: function(self) {
                if (self.find('#email').existsWithValue() === 0) {
                    self.find('#email').parent().addClass('has-error');
                    self.find('#email-error').show();
                    self.find('#email').focus();
                    self.find('#email-error span').html('Vui lòng nhập địa chỉ email');
                    return false;
                }
                return true;
            },

            phone: function(self) {
                if (self.find('#phone').existsWithValue() === 0) {
                    self.find('#phone').parent().addClass('has-error');
                    self.find('#phone-error').show();
                    self.find('#phone').focus();
                    self.find('#phone-error span').html('Vui lòng nhập số điện thoại');
                    return false;
                }
                return true;
            },
            account_number: function(self) {
                if (self.find('#account_number').existsWithValue() === 0) {
                    self.find('#account_number').parent().addClass('has-error');
                    self.find('#account_number-error').show();
                    self.find('#account_number').focus();
                    self.find('#account_number-error span').html('Vui lòng nhập số tài khoản');
                    return false;
                }
                return true;
            },

            account_holder: function(self) {
                if (self.find('#account_holder').existsWithValue() === 0) {
                    self.find('#account_holder').parent().addClass('has-error');
                    self.find('#account_holder-error').show();
                    self.find('#account_holder').focus();
                    self.find('#account_holder-error span').html('Vui lòng nhập chủ tài khoản');
                    return false;
                }
                return true;
            },
            
             branch_bank: function(self) {
                if (self.find('#branch_bank').existsWithValue() === 0) {
                    self.find('#branch_bank').parent().addClass('has-error');
                    self.find('#branch_bank-error').show();
                    self.find('#branch_bank').focus();
                    self.find('#branch_bank-error span').html('Vui lòng nhập chi nhánh ngân hàng');
                    return false;
                }
                return true;
            },
           
            password: function(self) {
                if (self.find('#password').existsWithValue() === 0) {
                    self.find('#password').parent().addClass('has-error');
                    self.find('#password-error').show();
                    self.find('#password').focus();
                    self.find('#password-error span').html('Vui lòng nhập mật khẩu');
                    return false;
                }
                return true;
            },
           
            repeatPasswd: function(self) {
                if (self.find('#confirmpassword').val() !== self.find('#password').val()) {
                    self.find('#confirmpassword').parent().addClass('has-error');
                    self.find('#confirmpassword-error').show();
                    self.find('#confirmpassword').focus();
                    self.find('#confirmpassword-error span').html('Vui lòng nhập lại mật khẩu');
                    return false;
                }
                return true;
            },

            

            checkUserExit: function(self, callback) {
                if (self.find('#username').existsWithValue() !== 0) {
                    $.ajax({
                        url: self.find('#username').data('link'),
                        type: 'GET',
                        data: {
                            'username': self.find('#username').val()
                        },
                        async: false,
                        success: function(result) {
                            result = $.parseJSON(result);
                            callback(result.success === 0);
                        }
                    });
                }
            },

            checkEmailExit: function(self, callback) {
                if (self.find('#email').existsWithValue() !== 0) {
                    $.ajax({
                        url: self.find('#email').data('link'),
                        type: 'GET',
                        data: {
                            'email': self.find('#email').val()
                        },
                        async: false,
                        success: function(result) {

                            result = $.parseJSON(result);
            
                            callback(result.success === 0);
                        }
                    });
                }
            },
            checkPhoneExit: function(self, callback) {
                if (self.find('#phone').existsWithValue() !== 0) {
                    $.ajax({
                        url: self.find('#phone').data('link'),
                        type: 'GET',
                        data: {
                            'phone': self.find('#phone').val()
                        },
                        async: false,
                        success: function(result) {
                            result = $.parseJSON(result);
                            callback(result.success === 0);
                        }
                    });
                }
            },

            checkCMND: function(self, callback) {
                if (self.find('#cmnd').existsWithValue() !== 0) {
                    $.ajax({
                        url: self.find('#cmnd').data('link'),
                        type: 'GET',
                        data: {
                            'cmnd': self.find('#cmnd').val()
                        },
                        async: false,
                        success: function(result) {
                            result = $.parseJSON(result);
                            callback(result.success === 0);
                        }
                    });
                }
            },
            /*check_BitcoinWalletAddress: function(self, callback) {
                if (self.find('#BitcoinWalletAddress').existsWithValue() !== 0) {
                    $.ajax({
                        url: self.find('#BitcoinWalletAddress').data('link'),
                        type: 'GET',
                        data: {
                            'wallet': self.find('#BitcoinWalletAddress').val()
                        },
                        async: false,
                        success: function(result) {
                            result = $.parseJSON(result);
                            callback(result.wallet === 0);
                        }
                    });
                }
            },*/
        };


        validate.init($(this));
        if (validate.userName($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#username').parent().addClass('has-success');
        }
        if (validate.cmnd($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#cmnd').parent().addClass('has-success');
        }
        if (validate.email($(this)) === false) {
            return false;
        } else {
            var checkEmail = null;
            isValidEmailAddress(self.find('#email').val(), function(callback) {
                checkEmail = !callback ? true : false;
            });
            if (checkEmail) {
                self.find('#email').parent().addClass('has-error');
                self.find('#email-error').show();
                self.find('#email').focus();
                self.find('#email-error span').html('Địa chỉ email không hợp lệ');
                return false;
            } else {
                validate.init($(this));
                self.find('#email').parent().addClass('has-success');
            }
        }

        if (validate.phone($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#phone').parent().addClass('has-success');
        }
        
        if (validate.account_number($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#account_number').parent().addClass('has-success');
        }

        if (validate.account_holder($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#account_holder').parent().addClass('has-success');
        }
        
        if (validate.branch_bank($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#branch_bank').parent().addClass('has-success');
        }
        

        if (validate.password($(this)) === false) {
            return false;
        } else {
            /*var checkpassword = null;
            isValidpass(self.find('#password').val(), function(callback) {
                checkpassword = !callback ? true : false;
            });
            if (checkpassword) {
                self.find('#password').parent().addClass('has-error');
                self.find('#password-error').show();
                self.find('#password').focus();
                self.find('#password-error span').html('Mật khẩu phải lớn hơn 6 ký tự và bao gồm số và chữ cái');
                return false;
            }
            else
            {*/
                validate.init($(this));
                self.find('#password').parent().addClass('has-success');
            //}
            
        }
        
        
        
        if (validate.repeatPasswd($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#confirmpassword').parent().addClass('has-success');
        }
        
       
         /*if (validate.BitcoinWalletAddress($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#BitcoinWalletAddress').parent().addClass('has-success');
        }*/

        var checkUser = null;
        var checkEmail = null;
        var checkPhone = null;
        var checkCMND = null;
        var check_BitcoinWalletAddress =null;

        validate.checkUserExit($(this), function(callback) {
            validate.init($(this));
            if (!callback) {
                self.find('#username').parent().addClass('has-error');
                self.find('#user-error').show();
                self.find('#user-error span').html('This user name is already exists');
                self.find('#password').val('');
                self.find('#password').parent().removeClass('has-success');
                self.find('#confirmpassword').val('');
                self.find('#confirmpassword').parent().removeClass('has-success');
                // self.find('#password2').val('');
                self.find('#password2').parent().removeClass('has-success');
                // self.find('#confirmpasswordtransaction').val('');
                self.find('#confirmpasswordtransaction').parent().removeClass('has-success');
                return false;
            } else {
                self.find('#username').parent().removeClass('has-error');
                self.find('#user-error').hide();
                self.find('#email').parent().removeClass('has-error');
                self.find('#email-error').hide();
                self.find('#phone').parent().removeClass('has-error');
                self.find('#phone-error').hide();
                self.find('#cmnd').parent().removeClass('has-error');
                self.find('#cmnd-error').hide();
                self.find('#BitcoinWalletAddress').parent().removeClass('has-error');
                self.find('#BitcoinWalletAddress-error').hide();
                self.find('#country').parent().removeClass('has-error');
                self.find('#country-error').hide();
                self.find('#password').parent().removeClass('has-error');
                self.find('#password-error').hide();
                self.find('#password2').parent().removeClass('has-error');
                self.find('#password2-error').hide();
                self.find('#confirmpassword').parent().removeClass('has-error');
                self.find('#confirmpassword-error').hide();
                self.find('#confirmpasswordtransaction').parent().removeClass('has-error');
                self.find('#confirmpasswordtransaction-error').hide();
                $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');
                self.find('#username').parent().addClass('has-success');
                checkUser = true;
            }
        });

        if (checkUser) {
            validate.checkEmailExit($(this), function(callback) {
                if (!callback) {
                    self.find('#email').parent().addClass('has-error');
                    self.find('#email-error').show();
                    self.find('#email-error span').html('This email is already exists');
                    self.find('#password').val('');
                    self.find('#password').parent().removeClass('has-success');
                    self.find('#confirmpassword').val('');
                    self.find('#confirmpassword').parent().removeClass('has-success');
                    // self.find('#password2').val('');
                    self.find('#password2').parent().removeClass('has-success');
                    // self.find('#confirmpasswordtransaction').val('');
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-success');
                    return false;
                } else {
                    self.find('#username').parent().removeClass('has-error');
                    self.find('#user-error').hide();
                    self.find('#email').parent().removeClass('has-error');
                    self.find('#email-error').hide();
                    self.find('#phone').parent().removeClass('has-error');
                    self.find('#phone-error').hide();
                    self.find('#cmnd').parent().removeClass('has-error');
                    self.find('#cmnd-error').hide();
                    self.find('#BitcoinWalletAddress').parent().removeClass('has-error');
                    self.find('#BitcoinWalletAddress-error').hide();
                    self.find('#country').parent().removeClass('has-error');
                    self.find('#country-error').hide();
                    self.find('#password').parent().removeClass('has-error');
                    self.find('#password-error').hide();
                    self.find('#password2').parent().removeClass('has-error');
                    self.find('#password2-error').hide();
                    self.find('#confirmpassword').parent().removeClass('has-error');
                    self.find('#confirmpassword-error').hide();
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-error');
                    self.find('#confirmpasswordtransaction-error').hide();
                    $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');
                    self.find('#email').parent().addClass('has-success');
                    checkEmail = true;
                }
            });
        };

        if (checkUser && checkEmail) {
            validate.checkPhoneExit($(this), function(callback) {
                if (!callback) {
                    self.find('#phone').parent().addClass('has-error');
                    self.find('#phone-error').show();
                    self.find('#phone-error span').html('This phone is already exists');
                    self.find('#password').val('');
                    self.find('#password').parent().removeClass('has-success');
                    self.find('#confirmpassword').val('');
                    self.find('#confirmpassword').parent().removeClass('has-success');
                    // self.find('#password2').val('');
                    self.find('#password2').parent().removeClass('has-success');
                    // self.find('#confirmpasswordtransaction').val('');
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-success');
                    return false;
                } else {
                    self.find('#username').parent().removeClass('has-error');
                    self.find('#user-error').hide();
                    self.find('#email').parent().removeClass('has-error');
                    self.find('#email-error').hide();
                    self.find('#phone').parent().removeClass('has-error');
                    self.find('#phone-error').hide();
                    self.find('#cmnd').parent().removeClass('has-error');
                    self.find('#cmnd-error').hide();
                    self.find('#BitcoinWalletAddress').parent().removeClass('has-error');
                    self.find('#BitcoinWalletAddress-error').hide();
                    self.find('#country').parent().removeClass('has-error');
                    self.find('#country-error').hide();
                    self.find('#password').parent().removeClass('has-error');
                    self.find('#password-error').hide();
                    self.find('#password2').parent().removeClass('has-error');
                    self.find('#password2-error').hide();
                    self.find('#confirmpassword').parent().removeClass('has-error');
                    self.find('#confirmpassword-error').hide();
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-error');
                    self.find('#confirmpasswordtransaction-error').hide();
                    $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');
                    self.find('#phone').parent().addClass('has-success');
                    checkPhone = true;
                }
            });
        };
        if (checkUser && checkEmail && checkPhone) {
            validate.checkCMND($(this), function(callback) {
                if (!callback) {
                    self.find('#cmnd').parent().addClass('has-error');
                    self.find('#cmnd-error').show();
                    self.find('#cmnd-error span').html('This citizenship card/passport no is already exists');
                    self.find('#password').val('');
                    self.find('#password').parent().removeClass('has-success');
                    self.find('#confirmpassword').val('');
                    self.find('#confirmpassword').parent().removeClass('has-success');
                    // self.find('#password2').val('');
                    self.find('#password2').parent().removeClass('has-success');
                    // self.find('#confirmpasswordtransaction').val('');
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-success');
                    return false;
                } else {
                    self.find('#username').parent().removeClass('has-error');
                    self.find('#user-error').hide();
                    self.find('#email').parent().removeClass('has-error');
                    self.find('#email-error').hide();
                    self.find('#phone').parent().removeClass('has-error');
                    self.find('#phone-error').hide();
                    self.find('#cmnd').parent().removeClass('has-error');
                    self.find('#cmnd-error').hide();
                    self.find('#country').parent().removeClass('has-error');
                    self.find('#country-error').hide();
                    self.find('#password').parent().removeClass('has-error');
                    self.find('#password-error').hide();
                    self.find('#password2').parent().removeClass('has-error');
                    self.find('#password2-error').hide();
                    self.find('#confirmpassword').parent().removeClass('has-error');
                    self.find('#confirmpassword-error').hide();
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-error');
                    self.find('#confirmpasswordtransaction-error').hide();
                    $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');
                    self.find('#cmnd').parent().addClass('has-success');
                    checkCMND = true;
                }
            });
        }
        /*if (checkUser && checkEmail && checkPhone && checkCMND) {
            validate.check_BitcoinWalletAddress($(this), function(callback) {
                if (!callback) {
                    self.find('#BitcoinWalletAddress').parent().addClass('has-error');
                    self.find('#BitcoinWalletAddress-error').show();
                    self.find('#BitcoinWalletAddress-error span').html('Wrong bitcoin wallet address!!');
                    self.find('#password').val('');
                    self.find('#password').parent().removeClass('has-success');
                    self.find('#confirmpassword').val('');
                    self.find('#confirmpassword').parent().removeClass('has-success');
                    // self.find('#password2').val('');
                    self.find('#password2').parent().removeClass('has-success');
                    // self.find('#confirmpasswordtransaction').val('');
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-success');
                    return false;
                } else {
                    self.find('#username').parent().removeClass('has-error');
                    self.find('#user-error').hide();
                    self.find('#email').parent().removeClass('has-error');
                    self.find('#email-error').hide();
                    self.find('#phone').parent().removeClass('has-error');
                    self.find('#phone-error').hide();
                    self.find('#cmnd').parent().removeClass('has-error');
                    self.find('#cmnd-error').hide();
                    self.find('#BitcoinWalletAddress').parent().removeClass('has-error');
                    self.find('#BitcoinWalletAddress-error').hide();
                    self.find('#country').parent().removeClass('has-error');
                    self.find('#country-error').hide();
                    self.find('#password').parent().removeClass('has-error');
                    self.find('#password-error').hide();
                    self.find('#password2').parent().removeClass('has-error');
                    self.find('#password2-error').hide();
                    self.find('#confirmpassword').parent().removeClass('has-error');
                    self.find('#confirmpassword-error').hide();
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-error');
                    self.find('#confirmpasswordtransaction-error').hide();
                    $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');
                    self.find('#BitcoinWalletAddress').parent().addClass('has-success');
                    check_BitcoinWalletAddress = true;
                }
            });
        }*/
    

        
        if(checkUser && checkEmail && checkPhone && checkCMND){
            jQuery('#page-preloader').show();
            jQuery('body').css({'overflow':'hidden'});
            $('.btn-register').hide();
            return true;
        }

        return false;

    });

});