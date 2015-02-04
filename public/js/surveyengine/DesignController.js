DesignController = function() {
    /**
     * Holds the current page number
     * @var int
     */
    this.currentPageNumber = 1;
    
    /**
     * Add question
     * @param   string
     * @returns void
     */
    this.addQuestion = function(type) {
        var path = "";
        switch(type){
            case "TEXT":
                path = "/js/surveyengine/templates/text.handlebars";
            break;
            default:
                path = "/js/surveyengine/templates/multipleChoice.handlebars";
            break;
        }
        var template;
        $.ajax({
            url: path,
            cache: false,
            success: function(source) {
                var data = {
                    pageId: 1,
                    surveyId: 1,
                    questionId: 0,
                    mode: "add",
                    questionText: "",
                    answers: [{ answerText: "", id: "", questionId: "" },
                              { answerText: "", id: "", questionId: "" },
                              { answerText: "", id: "", questionId: "" }] 
                };   
                template  = Handlebars.compile(source);
                $(".modal-body").html(template(data));
                $("#modal").modal('show');
                DesignController.addAnswerChoiceListeners();
            }               
        });     
    };
    
    /**
     * Edit question
     * @param   int
     * @returns void
     */
    this.editQuestion = function(questionId) {
        //before we load the page, we need to grab the defaults for the question
        var path = "/survey-api/question/read";
        $.ajax({
            url: path,
            cache: false,
            type: "POST",
            data: "questionId="+questionId,
            dataType: "json",
            success: function(callback) {
                var path = "";
                var isTextArea = "false";
                switch(callback.collection.renderId){
                    case "3":
                        path = "/js/surveyengine/templates/text.handlebars";
                    break;
                    case "4":
                        isTextArea = "true";
                        path = "/js/surveyengine/templates/text.handlebars";
                    break;
                    default:
                        path = "/js/surveyengine/templates/multipleChoice.handlebars";
                    break;
                }
                var template;
                $.ajax({
                    url: path,
                    cache: false,
                    success: function(source) {
                        var data = {
                            pageId: 1,
                            surveyId: 1,
                            questionId: questionId,
                            questionText: callback.collection.questionText,
                            answers: callback.collection.answers,
                            mode: "edit",
                            isTextArea: isTextArea
                        };
                        template  = Handlebars.compile(source);
                        $(".modal-body").html(template(data));
                        $("#modal").modal('show');
                        DesignController.addAnswerChoiceListeners();
                    }               
                });
            }       
        });     
    };
    
    /**
     * Add the listeners for the answer choices
     * @returns void
     */
    this.addAnswerChoiceListeners = function() {
        $('.glyphicon-plus').unbind( "click" );
        $('.glyphicon-minus').unbind( "click" );
        $('.glyphicon-plus').on('click', function () {
            var id = $("#answerChoices .row:last").attr('id');
            var appendDiv = $('#'+id).clone().appendTo('#answerChoices');
            var newId = ++id;
            appendDiv.attr('id', newId);
            
            var input = $("#"+appendDiv.attr('id')+" :input");
            input.attr('id', "answerChoice"+newId);
            input.attr('name', "answerChoice"+newId);
            input.val("");
            DesignController.addAnswerChoiceListeners();
        });
        
        $('.glyphicon-minus').on('click', function () {
            $("#answerChoices .row:last").remove();
        });
    };
    
    /**
     * Save the question
     * @returns void
     */
    this.save = function() {
        var path = "/surveyengine/question/add";
        $.ajax({
            url: path,
            cache: false,
            type: "POST",
            data: $("#question").serialize(),
            //dataType: "json",
            success: function(callback) {
                if(callback.result){
                    DesignController.loadPageView();
                    $("#resultModal .modal-header").html("SUCCESS");
                    $("#resultModal .modal-body").html("Your question was saved correctly");
                    $("#modal").modal('hide');
                    $("#resultModal").modal('show');
                    setTimeout(function(){
                        $("#resultModal").modal('hide');
                    }, 2000);
                }else{
                    $("#resultModal .modal-header").html("UH-OH");
                    $("#resultModal .modal-body").html("Your question was NOT saved correctly");
                    $("#modal").modal('hide');
                    $("#resultModal").modal('show');
                    setTimeout(function(){
                        $("#resultModal").modal('hide');
                    }, 3000);
                }
            }               
        });
    };
    
    /**
     * Load the page
     * @returns void
     */
    this.loadPageView = function() {
        var path = "/surveyengine/page/"+this.currentPageNumber;
        $.ajax({
            url: path,
            cache: false,
            type: "POST",
            data: "surveyId=1",
            dataType: "json",
            success: function(callback) {
                var path = "/js/surveyengine/templates/pageView.handlebars";
                var template;
                $.ajax({
                    url: path,
                    cache: true,
                    success: function(source) {
                        var pageName = callback.page.name.length>0? callback.page.name : '+ Add Page Title';
                        
                        

                        var data = {
                           surveyTitle: callback.survey.name,
                           surveyId:    callback.survey.id,
                           pageId:      callback.page.id,
                           pageTitle:   pageName,
                           questions:   callback.questions
                        };   
                        template  = Handlebars.compile(source);
                        $("#survey").html(template(data));
                    }               
                });
            }       
        });
    };
    
    /**
     * Change the page name
     * @returns void
     */
    this.showChangePageName = function() {
        var path = "/js/surveyengine/templates/changePageName.handlebars";
        var template;
        $.ajax({
            url: path,
            cache: true,
            success: function(source) {
                //var pageName = callback.page.name.length>0? callback.page.name : '+ Add Page Title';
                var data = {
                   pageTitle: $("#pageTitle a").html(),
                   surveyId: 1,
                   pageId: DesignController.currentPageNumber
                };
                template  = Handlebars.compile(source);
                $("#pageNameModal .modal-body").html(template(data));
                $("#pageNameModal").modal('show'); 
            }               
        });
    };
    
    /**
     * Save the page name
     * @returns void
     */
    this.savePageName = function() {
        var path = "/survey-api/page/update";
        $.ajax({
            url: path,
            cache: false,
            type: "POST",
            data: $("#changePageNameForm").serialize()+"&sortOrder=1",
            dataType: "json",
            success: function(callback) {
                if(callback.result){
                    $("#pageTitle a").html($("#name").val()); //update with the new page name
                    $("#resultModal .modal-header").html("SUCCESS");
                    $("#resultModal .modal-body").html("The page name was saved correctly");
                    $("#pageNameModal").modal('hide');
                    $("#resultModal").modal('show');
                    setTimeout(function(){
                        $("#resultModal").modal('hide');
                    }, 2000);
                }else{
                    $("#resultModal .modal-header").html("UH-OH");
                    $("#resultModal .modal-body").html("The page name was NOT saved correctly");
                    $("#pageNameModal").modal('hide');
                    $("#resultModal").modal('show');
                    setTimeout(function(){
                        $("#resultModal").modal('hide');
                    }, 3000);
                }
            }               
        });
    };
    
    /**
     * Delete an existing question
     * @param  int id
     * @returns void
     */
    this.deleteQuestion = function(id) {
        var path = "/survey-api/question/delete";
        $.ajax({
            url: path,
            cache: false,
            type: "POST",
            data: "questionId="+id,
            dataType: "json",
            success: function(callback) {
                if(callback.result){
                    DesignController.loadPageView();
                    $("#resultModal .modal-header").html("SUCCESS");
                    $("#resultModal .modal-body").html("Your question was deleted");
                    $("#modal").modal('hide');
                    $("#resultModal").modal('show');
                    setTimeout(function(){
                        $("#resultModal").modal('hide');
                    }, 2000);
                }else{
                    $("#resultModal .modal-header").html("UH-OH");
                    $("#resultModal .modal-body").html("Your question was NOT deleted");
                    $("#modal").modal('hide');
                    $("#resultModal").modal('show');
                    setTimeout(function(){
                        $("#resultModal").modal('hide');
                    }, 3000);
                }
            }               
        });
    };
    
    /**
     * Load the default events for the page
     * @returns void
     */
    this.loadDefaultEvents = function() {
        $("#save").click(function(){
            DesignController.save();
        }); 
        $("#pageNameSave").click(function(){
            DesignController.savePageName();
        });
    };
};
DesignController = new DesignController();

//register our new if helper (allows comparisons)
Handlebars.registerHelper("if", function(conditional, options) {
    if (options.hash.desired === options.hash.type) {
      return options.fn(this);
    } else {
      return options.inverse(this);
    }
});