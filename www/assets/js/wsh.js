var WSHTranslator = (function() {
    function WSHTranslator() {

    }
    WSHTranslator.prototype.parseFromObj = function( object ) {
        if( typeof object !== "object" )
            throw "The parameter in WSHTranslator.parseFrom method must be typeof object.";

        var string = JSON.stringify( object );

        if( typeof string === "string" )
            return string;
        else
            throw "The object conversion to string has failed in WSHTranslator.parseFrom method.";
    };
    WSHTranslator.prototype.parseToObj = function( string ) {
        if( typeof string !== "string" )
            throw "The parameter in WSHTranslator.parseTo method must be typeof string.";

        if( this.isJson( string ) ) {
            return JSON.parse( string );
        }
        else {
            console.log( "Unsupported type format: " + typeof string );
        }
    };
    WSHTranslator.prototype.isJson = function( string ) {
        string = typeof string !== "string" ? JSON.stringify( string ) : string;

        try {
            string = JSON.parse( string );
        }
        catch( e ) {
            console.log(e);
            return false;
        }

        return typeof string === "object" && string !== null;
    };
    return WSHTranslator;
})();

var WSHConnection = (function() {
    function WSHConnection( connectionOptions, events ) {
        // WSH event class
        this.events = events;
        // connection part of provided initial or default options
        this.options = connectionOptions || {};
        // will hold base url of WebSockets connection (after this.initialize() call)
        this.baseUrl = null;
        // will hold connection of WebSocket class (after this.initialize() call)
        this.connection = null;
    }
    WSHConnection.prototype.initialize = function() {
        var wsUrl = this.options.url;
        var wsUrlPort = this.options.port;

        if( !wsUrl ) {
            throw "The url of connection was not specified in WSHConnection class.";
        }

        this.baseUrl = wsUrl + ( wsUrlPort ? ":" + wsUrlPort : "" );
        this.connection = new WebSocket( this.baseUrl );
        this.bindEvents();

    };
    WSHConnection.prototype.send = function( message ) {

        // send via WebSocket class send method
        this.connection.send( message );
    };
    WSHConnection.prototype.onMessage = function() {
        var self = this;

        this.connection.onmessage = function( e ) {
            var message = e.data;
            self.events.dispatcher( message );
        };
    };
    WSHConnection.prototype.onOpen = function() {
        this.connection.onopen = function( e ) {
            console.log( "WSH connection successfully initialized!" );
        };
    };
    WSHConnection.prototype.bindEvents = function() {
        this.onMessage();
        this.onOpen();
    };
    return WSHConnection;
})();

var WSEvents = (function() {
    function WSEvents( translator ) {
        this.translator = translator;
        this.events = {};
    }
    WSEvents.prototype.dispatcher = function( message ) {
        // translate the string received from the server to json object
        var obj = this.translator.parseToObj( message );
        //console.log( "Dispatcher has received message:" );
        //console.log( obj );
        if( !obj || typeof obj !== "object" )
            throw "Message was malformed";

        if( !obj.action )
            throw "Event must be specified in message object.";

        // create and dispatch event
        var detail = { detail: obj };
        var event = new CustomEvent( obj.action, detail );
        window.dispatchEvent( event );
    };
    WSEvents.prototype.addEvListener = function( event, callback ) {
        if( typeof event !== "string" )
            throw "Type of parameter event must by string in WSWrapper.on method.";

        window.addEventListener( event, function( e ) {
            if( typeof callback === "function" ) {
                var detail = e.detail || {};
                detail.data = detail.data || {};
                callback( e, detail );
            }
        });
    };
    return WSEvents;
})();

var WSWrapper = (function() {
    function WSWrapper( connection, events ) {
        this.connection = connection;
        this.events     = events;
    }
    WSWrapper.prototype.start = function() {
        try {
            this.connection.initialize();
            this.getResourceIdEvent();
        }
        catch( error ) {
            console.log( error );
        }
    };
    WSWrapper.prototype.on = function( event, callback ) {
        this.events.addEvListener( event, callback );
    };
    WSWrapper.prototype.send = function( action, message ) {
        var messageString = "";

        if( typeof message === "string" )
            messageString = message;
        else if( typeof message === "object" ) {
            var finalObj = {};
            if( !message.action && typeof action === "string" ) {
                finalObj.action = action;
                finalObj.data = message;
            }
            else
                finalObj = message;

            messageString = this.events.translator.parseFromObj( finalObj );
        }

        console.log( "sending message:", messageString );

        this.connection.send( messageString );
    };
    WSWrapper.prototype.getResourceIdEvent = function() {
        var self = this;
        this.on( "init", function( e, detail ) {
            var userResourceId = parseInt( detail.data.resource_id || 0 );
            globalInfo.wsResourceId = userResourceId;
            var userId = globalInfo.currentUserId;

            if( globalInfo.wsResourceId && userId ) {
                self.send( "init", {
                    user_id: userId
                });
            }
        });
        this.on( "initialized", function( e, detail ) {

            console.log( "successfully initialized with userId" );
            console.log( detail );
        });
    };
    return WSWrapper;
})();


var WSH = (function() {
    function WSH( options ) {
        this.options = options;
        this.connectionOptions = options && options.connection ? options.connection : {};

        var translator  = new WSHTranslator;
        var events      = new WSEvents( translator );
        var connection  = new WSHConnection( this.connectionOptions, events );
        // the heart of aplication
        this.core = new WSWrapper( connection, events );
        // start application
        this.core.start();
    }
    return WSH;
})();




/*
 Example of usage
/

var wsh = new WSH({
    connection: {
        url: "ws://localhost",
        port: "8080"
    }
});

/*wsh.core.on( "test", function( e ) {
    console.log("it works!!");
    console.log(e);
});
*/
