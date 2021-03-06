<?php

use Haijin\Parser\Parser;
use Haijin\Parser\Parser_Definition;

$spec->describe( "When matching a literal particle", function() {

    $this->let( "parser", function() {

        return new Parser( $this->parser_definition );

    });

    $this->describe( "at the beginning of an expression", function() {

        $this->let( "parser_definition", function() {

            return ( new Parser_Definition() )->define( function($parser) {

                $parser->expression( "root",  function() {

                    $this->matcher( function() {

                        $this->str( "123" ) ->str( "321" );

                    });

                    $this->handler( function() {

                        return "parsed";

                    });

                });

            });

        });

        $this->describe( "matches a valid expression", function() {

            $this->let( "input", function() {
                return "123321";
            });

            $this->it( "evaluates the handler closure", function() {

                $result = $this->parser->parse_string( $this->input );

                $this->expect( $result ) ->to() ->equal( "parsed" );

            });

        });

        $this->describe( "fails for an invalid expression", function() {

            $this->let( "input", function() {
                return "1321";
            });

            $this->it( "raises an error", function() {

                $this->expect( function() {

                    $this->parser->parse_string( $this->input );

                }) ->to() ->raise(
                    \Haijin\Parser\Unexpected_Expression_Error::class,
                    function($error) {

                        $this->expect( $error->getMessage() ) ->to() ->equal(
                            'Unexpected expression "1321". At line: 1 column: 1.'
                        );
                }); 

            });

        });

        $this->describe( "fails for an invalid next particle", function() {

            $this->let( "input", function() {
                return "1233";
            });

            $this->it( "raises an error", function() {

                $this->expect( function() {

                    $this->parser->parse_string( $this->input );

                }) ->to() ->raise(
                    \Haijin\Parser\Unexpected_Expression_Error::class,
                    function($error) {

                        $this->expect( $error->getMessage() ) ->to() ->equal(
                            'Unexpected expression "3". At line: 1 column: 4.'
                        );
                }); 

            });

        });

    });

    $this->describe( "in the middle of an expression", function() {

        $this->let( "parser_definition", function() {

            return ( new Parser_Definition() )->define( function($parser) {

                $parser->expression( "root",  function() {

                    $this->matcher( function() {

                        $this->str( "1" ) ->str( "2" )  ->str( "3" );

                    });

                    $this->handler( function() {

                        return "parsed";

                    });

                });

            });

        });

        $this->describe( "matches a valid expression", function() {

            $this->let( "input", function() {
                return "123";
            });

            $this->it( "evaluates the handler closure", function() {

                $result = $this->parser->parse_string( $this->input );

                $this->expect( $result ) ->to() ->equal( "parsed" );

            });

        });

        $this->describe( "fails for an invalid expression", function() {

            $this->let( "input", function() {
                return "1z3";
            });

            $this->it( "raises an error", function() {

                $this->expect( function() {

                    $this->parser->parse_string( $this->input );

                }) ->to() ->raise(
                    \Haijin\Parser\Unexpected_Expression_Error::class,
                    function($error) {

                        $this->expect( $error->getMessage() ) ->to() ->equal(
                            'Unexpected expression "z3". At line: 1 column: 2.'
                        );
                }); 

            });

        });

        $this->describe( "fails for an invalid next particle", function() {

            $this->let( "input", function() {
                return "12z";
            });

            $this->it( "raises an error", function() {

                $this->expect( function() {

                    $this->parser->parse_string( $this->input );

                }) ->to() ->raise(
                    \Haijin\Parser\Unexpected_Expression_Error::class,
                    function($error) {

                        $this->expect( $error->getMessage() ) ->to() ->equal(
                            'Unexpected expression "z". At line: 1 column: 3.'
                        );
                }); 

            });

        });

    });

    $this->describe( "at the end of an expression", function() {

        $this->let( "parser_definition", function() {

            return ( new Parser_Definition() )->define( function($parser) {

                $parser->expression( "root",  function() {

                    $this->matcher( function() {

                        $this->str( "321" ) ->str( "123" );

                    });

                    $this->handler( function() {

                        return "parsed";

                    });

                });

            });

        });

        $this->describe( "matches a valid expression", function() {

            $this->let( "input", function() {
                return "321123";
            });

            $this->it( "evaluates the handler closure", function() {

                $result = $this->parser->parse_string( $this->input );

                $this->expect( $result ) ->to() ->equal( "parsed" );

            });

        });

        $this->describe( "fails for an invalid expression", function() {

            $this->let( "input", function() {
                return "3211";
            });

            $this->it( "raises an error", function() {

                $this->expect( function() {

                    $this->parser->parse_string( $this->input );

                }) ->to() ->raise(
                    \Haijin\Parser\Unexpected_Expression_Error::class,
                    function($error) {

                        $this->expect( $error->getMessage() ) ->to() ->equal(
                            'Unexpected expression "1". At line: 1 column: 4.'
                        );
                }); 

            });

        });

    });

});