<?php

namespace spec\LopSpec\Formatter\Presenter;

use LopSpec\Formatter\Presenter\Differ\Differ;
use LopSpec\ObjectBehavior;

class TaggedPresenterSpec extends ObjectBehavior
{
    function it_wraps_string_into_tags()
    {
        $this->presentString('string')
             ->shouldReturn('<value>string</value>');
    }
    function it_wraps_value_into_tags()
    {
        $this->presentValue('string')->shouldReturn('<value>"string"</value>');
    }
    function let(Differ $differ)
    {
        $this->beConstructedWith($differ);
    }
}
