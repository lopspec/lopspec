Object Construction
===================

In **lopspec** specs the object you are describing is not a separate variable
but is `$this`. So instead of writing something like:

.. code-block:: php

    <?php

    namespace spec;

    use LopSpec\ObjectBehavior;

    class MarkdownSpec extends ObjectBehavior
    {
        function it_converts_plain_text_to_html_paragraphs()
        {
            $markdown = new Markdown();
            $markdown->toHtml("Hi, there")->shouldReturn("<p>Hi, there</p>");
        }
    }

as you might with other tools, you write:

.. code-block:: php

    <?php

    namespace spec;

    use LopSpec\ObjectBehavior;

    class MarkdownSpec extends ObjectBehavior
    {
        function it_converts_plain_text_to_html_paragraphs()
        {
            $this->toHtml("Hi, there")->shouldReturn("<p>Hi, there</p>");
        }
    }

On consequence this means that you do not construct the object you are
describing in the examples. Instead **lopspec** handles the creation of the
object you are describing when you run the specs.

The default way **lopspec** does this is the same as ``new Markdown()``.
If it does not need any values or dependencies to be passed to it then this is
fine but for many objects this will not be good enough. You can tell **lopspec**
how you want it to create the object though.

Using the Constructor
---------------------

You can tell **lopspec** to pass values to the constructor when it constructs the object:

.. code-block:: php

    <?php

        namespace spec;

        use LopSpec\ObjectBehavior;
        use Markdown\Writer;

        class MarkdownSpec extends ObjectBehavior
        {
            function it_outputs_converted_text(Writer $writer)
            {
                $this->beConstructedWith($writer);
                $writer->writeText("<p>Hi, there</p>")->shouldBeCalled();

                $this->outputHtml("Hi, there");
            }
        }

Using a Factory Method
----------------------

You may not want to use the constructor but use static factory methods to create the class.
This allows you to create it in different ways for different use cases since you can
only have a single constructor in PHP.

.. code-block:: php

    <?php

        use Markdown\Writer;

        class Markdown
        {
            public static function createForWriting(Writer $writer)
            {
                $markdown = new Self();
                $markdown->writer = $writer;

                return $markdown;
            }
        }

You can tell **lopspec** this is how you want to construct the object as follows:

.. code-block:: php

    <?php

        namespace spec;

        use LopSpec\ObjectBehavior;
        use Markdown\Writer;

        class MarkdownSpec extends ObjectBehavior
        {
            function it_outputs_converted_text(Writer $writer)
            {
                $this->beConstructedThrough('createForWriting', [$writer]);
                $writer->writeText("<p>Hi, there</p>")->shouldBeCalled();

                $this->outputHtml("Hi, there");
            }
        }

Where the first argument is the method name and the second an array of the values
to pass to that method.

Overriding
----------

To avoid repetition you can tell **lopspec** how to construct the object in `let`.
However, you may have a single example that needs constructing in a different way.
You can do this by calling ``beConstructedWith`` again in the example. The last time you
call ``beConstructedWith`` will determine how **lopspec** constructs the object:

.. code-block:: php

    <?php

        namespace spec;

        use LopSpec\ObjectBehavior;
        use Markdown\Writer;

        class MarkdownSpec extends ObjectBehavior
        {
            function let(Writer $writer)
            {
                $this->beConstructedWith($writer, true);
            }

            function it_outputs_converted_text(Writer $writer)
            {
                // constructed with second argument set to true
                // ...
            }

            function it_does_something_if_argument_is_false(Writer $writer)
            {
                $this->beConstructedWith($writer, false);
                // constructed with second argument set to false
                // ...
            }
        }
