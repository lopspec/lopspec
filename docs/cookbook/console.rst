Running lopspec
===============

The lopspec console command uses Symfony's console component. This means
that it inherits the `default Symfony console command and options <http://symfony.com/doc/current/components/console/usage.html>`_.

**lopspec** has an additional global option to let you specify a config file
other than `lopspec.yml` or `lopspec.yml.dist`:

.. code-block:: bash

    $ bin/lopspec run --config path/to/different-lopspec.yml

or:

.. code-block:: bash

    $ bin/lopspec run -c path/to/different-lopspec.yml

Read more about this in the :doc:`Configuration Cookbook </cookbook/configuration>`

Also of note is that using the `--no-interaction` command means that no
code generation will be done.


Describe Command
----------------

The describe command creates a specification for a class:

.. code-block:: bash

    $ php bin/lopspec describe ClassName

Will generate a specification ClassNameSpec in the spec directory.

.. code-block:: bash

    $ php bin/lopspec describe Namespace/ClassName

Will generate a namespaced specification Namespace\ClassNameSpec.
Note that / is used as the separator. To use \ it must be quoted:

.. code-block:: bash

    $ php bin/lopspec describe "Namespace\ClassName"

The describe command has no additional options. It will create a spec class in the `spec`
directory. To configure a different path to the specs you can use
:ref:`suites <configuration-suites>` in the configuration file.

Run Command
-----------

The run command runs the specs:

.. code-block:: bash

    $ php bin/lopspec run

Will run all the specs in the `spec` directory.

.. code-block:: bash

    $ php bin/lopspec run spec/ClassNameSpec.php

Will run only the ClassNameSpec. You can run just the specs in a directory
with:

.. code-block:: bash

    $ php bin/lopspec run spec/Markdown

Which will run any specs found in `spec/Markdown` and its subdirectories.
Note that it is the spec location and not namespaces that are used to decide which
specs to run. Any spec which has a namespace which does not match its file path
will be ignored.

By default, you will be asked whether missing methods and classes should
be generated. You can suppress these prompts and automatically choose not
to generate code with:

.. code-block:: bash

    $ php bin/lopspec run --no-code-generation

You can choose to stop on failure and avoid running the remaining
specs with:

.. code-block:: bash

    $ php bin/lopspec run --stop-on-failure

You can choose the output format with the format option e.g.:

.. code-block:: bash

    $ php bin/lopspec run --format=dot

The formatters available by default are:

* progress (default)
* html
* pretty
* junit
* dot

More formatters can be added by :doc:`extensions</cookbook/extensions>`.
