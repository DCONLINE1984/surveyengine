/**
 * The question bank controller
 * @author Dean Clow
 */

QuestionBankController = function() {
    /**
     * Holds the current question bank id
     * @var Int
     */
    this.currentQuestionId = 0;
    
    /**
     * Load the question bank
     * @returns void
     */
    this.loadQuestionBank = function() {
        var url = "/survey-api/question/read";
        $.ajax({
            url: url,
            cache: false,
            success: function(callback) {
                var html = "";
                $.each( callback.collection, function(key, value) {
                    html += '<a href="javascript:QuestionBankController.modifyQuestion(\''+value.renderId+'\', '+value.id+');"><i class="fa fa-plus-circle"></i></a> ' + value.questionText + '<br />';
                });
                $("#questionBankListing").html(html);
            }               
        });    
    };
    
    /**
     * Save the question
     * @returns void
     */
    this.addQuestion = function() {
        DesignController.save();
    };
    
    /**
     * Add question
     * @param   string
     * @param   int
     * @returns void
     */
    this.modifyQuestion = function(type, questionId) {
        var me   = this;
        me.currentQuestionId = questionId;
        //before we load the page, we need to grab the defaults for the question
        var path = "/survey-api/question/read";
        $.ajax({
            url: path,
            cache: false,
            type: "POST",
            data: "questionId="+questionId,
            dataType: "json",
            success: function(callback) {
                callback.collection = callback.collection[0];
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
                    case "5":
                        path = "/js/surveyengine/templates/rating.handlebars";
                    break;
                    case "6":
                        path = "/js/surveyengine/templates/dropdown.handlebars";
                    break;
                    case "7":
                        path = "/js/surveyengine/templates/html.handlebars";
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
                            pageNumber: me.currentPageNumber,
                            surveyId: DesignController.surveyId,
                            questionId: questionId,
                            questionText: callback.collection.questionText,
                            headingText: callback.collection.header,
                            answers: callback.collection.answers,
                            questions: callback.collection.answers,
                            mode: "add",
                            isTextArea: isTextArea
                        };
                        template  = Handlebars.compile(source);
                        $("#addQuestionBankModal .modal-body").html(template(data));
                        $("#addQuestionBankModal").modal('show');
                        DesignController.addAnswerChoiceListeners();
                    }               
                });
            }       
        });     
    };
    
    /**
     * Seach the bank
     * @returns void
     */
    this.search = function() {
        var searchText = $("#searchQuestionBank").val();
        var url = "/survey-api/question/read";
        $.ajax({
            url: url,
            cache: false,
            type: "POST",
            data: "questionId=6",
            success: function(callback) {
                $("#questionBankListing").show();
                var html = "";
                $.each( callback.collection, function(key, value) {
                    html += '<a href="javascript:QuestionBankController.modifyQuestion(\''+value.renderId+'\', '+value.id+');"><i class="fa fa-plus-circle"></i></a> ' + value.questionText + '<br />';
                });
                $("#questionBankListing").html(html);
            }               
        });
    };
};
var QuestionBankController = new QuestionBankController();