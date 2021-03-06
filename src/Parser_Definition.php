<?php

namespace Haijin\Parser;

use Haijin\Instantiator\Create;
use Haijin\File_Path;
use Haijin\Dictionary;

class Parser_Definition
{
    protected $before_parsing_closure;
    protected $expressions_by_name;
    protected $methods;

    /// Initializing

    public function __construct()
    {
        $this->before_parsing_closure = null;

        $this->expressions_by_name = Create::a( Dictionary::class )->with();

        $this->methods = Create::a( Dictionary::class )->with();
    }

    /// Accessing

    public function get_before_parsing_closure()
    {
        return $this->before_parsing_closure;
    }

    public function get_expression_named($expression_name, $absent_closure = null, $binding = null)
    {
        return $this->expressions_by_name->at_if_absent(
                    $expression_name,
                    $absent_closure,
                    $binding
                );
    }

    public function get_expressions_in( $expressions_names )
    {
        return $expressions_names->collect( function($expression_name) {

                return $this->get_expression_named( $expression_name );

            }, $this );
    }

    public function custom_method_at($method_name, $absent_closure = null, $binding = null )
    {
        return $this->methods->at_if_absent( $method_name, $absent_closure, $binding );
    }

    /// Defining

    public function define($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        $closure->call( $binding, $this );

        return $this;
    }


    public function define_in_file($file_path)
    {
        if( is_string( $file_path ) ) {
            $file_path = Create::a( File_Path::class )->with( $file_path );
        }

        return $this->define( function($parser) use($file_path) {
            require( $file_path->to_string() );
        });
    }

    /// DSL

    public function before_parsing($closure)
    {
        $this->before_parsing_closure = $closure;
    }

    public function expression($name, $definition_closure)
    {
        $expression = Create::an( Expression::class )->with( $name );

        $definition_closure->call( $expression );

        $this->add_expression( $expression );
    }

    protected function add_expression($expression)
    {
        $this->expressions_by_name[ $expression->get_name() ] = $expression;
    }

    protected function def( $method_name, $closure)
    {
        $this->methods[ $method_name ] = $closure;
    }

}