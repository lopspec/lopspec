<?php

namespace spec\LopSpec\Formatter\Presenter\Differ;

use LopSpec\Formatter\Presenter\Differ\StringEngine;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;
use SebastianBergmann\Exporter\Exporter;

class ObjectEngineSpec extends ObjectBehavior
{
    function it_converts_objects_to_string_and_diffs_the_result(
        Exporter $exporter,
        StringEngine $stringDiffer
    )
    {
        $exporter->export(Argument::type('DateTime'))
                 ->willReturn('DateTime');
        $exporter->export(Argument::type('ArrayObject'))
                 ->willReturn('ArrayObject');
        $stringDiffer->compare('DateTime', 'ArrayObject')
                     ->willReturn('-DateTime+ArrayObject');
        $diff = $this->compare(new \DateTime(), new \ArrayObject());
        $diff->shouldBe('-DateTime+ArrayObject');
    }
    function it_does_not_support_scalars()
    {
        $this->supports(1, 2)->shouldReturn(false);
    }
    function it_is_a_differ_engine()
    {
        $this->shouldHaveType('LopSpec\Formatter\Presenter\Differ\EngineInterface');
    }
    function it_only_supports_objects()
    {
        $this->supports(new \StdClass(), new \StdClass())->shouldReturn(true);
    }
    function let(Exporter $exporter, StringEngine $stringDiffer)
    {
        $this->beConstructedWith($exporter, $stringDiffer);
    }
}
