    
//  考试试卷
	

	// show the time
    $(".showTime").click(function(){
        $(".Countdown").transition('slide left');
    });

    $(".showTime").hover(function(){
        $('.showTime').transition('jiggle');   
    })





    // init answer (when refresh the page)
    $(function(){

        // get data when reflash 
        var storage = window.localStorage;                
        for(var i=0, len = storage.length; i  <  len; i++){
            var key = storage.key(i);     
            var value = storage.getItem(key); 
            // console.log(key + "=" + value);
            var _this = $("input[name='"+key+"']");     // gain the select
            var type = _this.attr('type');              // gain the type
            // console.log(type);
            if(type == 'radio') {                       // if type is 'radio' add the 'checked' property
                _this.each(function(){                  // it has many inputs
                    var _thisValue = $(this);
                    if(_thisValue.val() == value){
                        _thisValue = $("input[value='"+value+"']");
                        _thisValue.attr('checked',true);    
                    }
                  
                });
            } else if(type == 'text'){                  // if type is 'text' make its value equels key
                _this.val(value);
            }
            $("#"+key).addClass('positive');            // add class 'positive' to answer_sheet
        }

        // get percent when reflash
        Progress();

    });








    // answer_sheet change color(radio)
    $("input[type='radio']").on('click',function(){
        var question_id = $(this).attr('name');
        var answer = $(this).val();
        if(answer != ""){
            $("#"+question_id).addClass('positive');        // add class 'positive' to answer_sheet
            localStorage.setItem(question_id, answer);    // store the item by name

        }
        Progress();
     });
    // answer_sheet change color(text)
     $("input[type='text']").on('blur',function(){
        var question_id = $(this).attr('name');
        var answer = $(this).val();
        if(answer != ""){
            $("#"+question_id).addClass('positive');        // add class 'positive' to answer_sheet
            localStorage.setItem(question_id, answer);    // store the item by name

        }else{
            $("#"+question_id).removeClass('positive');        // add class 'positive' to answer_sheet
            localStorage.removeItem(question_id);    // store the item by name
        }
        Progress();
 
    });


     $(".goToTheSheet").click(function(){
        var question_id = $(this).attr('id');
        // alert(question_id);
       var target_top = $("input[name='"+question_id+"']").offset().top-300;
       $("html,body").animate({scrollTop: target_top}, 500);
     });

   

    // post the answer
    $(".confirm").click(function(){
        var str = IsAnswerAll();
        // did not answer all
        if(!str){
            $('.ui.basic.modal')
                .modal({
                     // post ahead
                     onApprove : function(){
                        postAnswer("提前提交试卷成功!")
                    }
                })
                .modal('show')
            ;
        }else {
            postAnswer("提交试卷成功!");
        }
    });

 

    // count down
    // month 0-11 
    var Utime = new Date(untilTime);
    $(".count_down").countdown({until: Utime, onExpiry : postAnswer, compact: true});       // 直到规定时间, 时间到期调用提交函数(后期可增加时间到期,自动填充未参加学生,成绩为0的情况)

    // post the answer
    function postAnswer(str="时间已到,试卷已经提交!"){
        var storage = window.localStorage;
        $('#alert_text').html(str);
        $.post(CheckAnswer, {answer:storage, testpaper_id:paper_id}, function(data){
            if(data === true){
                $('.small.modal')
                .modal({
                    onApprove : function(){
                        window.location.href = index;   
                    },
                })
                .modal('show')
                ;
            }else{
                alert("成绩已经存在!");
                window.location.href = index;
            }
        });

        storage.clear();
    }


    // check by answersheet class
    function IsAnswerAll() {
        var str=true;
        $(".goToTheSheet").each(function(){
            var _this = $(this);
            var status = _this.hasClass('positive');
            if(!status) {
                str = false;
            }
        });
        return str;     
    }

    // progress
    function Progress() {
        var sheet = $(".goToTheSheet").length;
        var p = $(".positive").length;
        $('#subject_progress')
        .progress({
            total : sheet,
            percent : (p/sheet)*100 
        });
    }







    
