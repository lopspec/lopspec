<?php

namespace spec\LopSpec\Formatter\Html;

use LopSpec\Exception\Exception;
use LopSpec\Formatter\Presenter\Differ\Differ;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class HtmlPresenterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldImplement('LopSpec\Formatter\Presenter\PresenterInterface');
    }
    function it_presents_the_code_around_where_exception_was_thrown(Exception $e)
    {
        $e->getCause()->willReturn(new \ReflectionClass($this));
        $this->presentException($e, true);
    }
    function let(Differ $differ)
    {
        $this->beConstructedWith($differ);
    }
}
