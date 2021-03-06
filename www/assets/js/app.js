var App = (function() {
    function App() {
        this.wrap = $("#chat");
        this.wsh = null;
    }
    App.prototype.init = function() {
        var self = this;
        this.ui();
        this.initWSH(function() {
            self.events();
        });
    };
    App.prototype.ui = function() {
        this.scrollChatWindow();
    };
    App.prototype.scrollChatWindow = function() {
        var container = this.wrap.find( ".chatMessages" );
        container.scrollTop(container.prop('scrollHeight'));
    };
    App.prototype.initWSH = function( callback ) {
        this.wsh = new WSH({
            connection: {
                url: "ws://localhost",
                port: "8080"
            }
        });

        if( typeof callback === "function" ) {
            callback();
        }
    };
    App.prototype.events = function() {
        var self = this;

        this.wrap.on( "click", ".messageSend", function( e ) {
            e.preventDefault();

            var form = $(this).closest( "form" );
            var messageTextInput = form.find( ".messageText" );
            var message = messageTextInput.val();
            messageTextInput.val("");

            console.log( form );
            console.log( message );

            if( message.trim() ) {
                var data = {
                    message: message.trim(),
                    fromUserId: globalInfo.currentUserId,
                    conversationId: $( "li.conversation.active" ).data( "conversation_id" ) || 0
                };
                console.log(data);
                self.wsh.core.send( "sendMessage", data );
            }



        });
        this.wsh.core.on( "receivemessage", function( e ) {
            var data = e.detail.data || {};
            $.each( data, function( i, v ) {
                var template = $(v);
                $("#chatWindow-" + i).html( template.html() );
                self.scrollChatWindow();

                self.wrap.find( ".chatConversations li[data-conversation_id='"+ i +"'] .lastMessageText" ).text( template.find("li:last-child").text() );
            });
            console.log(e);
            //console.log(data);
        });
    };
    App.prototype.receiveMessages = function() {

    };
    return App;
})();


$(document).ready(function() {
    var app = new App;
    app.init();
});