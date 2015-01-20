Configuration
=============

Some things in lopspec can be configured in a ``lopspec.yml`` or
``lopspec.yml.dist`` file in the root of your project (the directory where you
run the ``lopspec`` command).

You can use a different config file name and path with the ``--config`` option:

.. code-block:: bash

    $ bin/lopspec run --config path/to/different-lopspec.yml

You can also specify default values for config variables across all repositories by creating
the file ``.lopspec.yml`` in your home folder (Unix systems). Lopspec will use your personal preference for
all settings that are not defined in the project's configuration.

.. _configuration-suites:

PSR-4
-----

**lopspec** assumes a PSR-4 mapping of namespaces to the src and spec directories by default.
So for example running:

.. code-block:: bash

    $ bin/lopspec describe Acme/Text/Markdown

Will create a spec in the ``spec/Text/MarkdownSpec.php`` file and the class will
be created in ``src/Text/Markdown.php``

To use PSR-4 you configure the ``namespace`` option in a suite to the part that
should be omitted from the directory structure:

.. code-block:: yaml

    suites:
        acme_suite:
            namespace: Acme\Text

With this config running:

.. code-block:: bash

    $ bin/lopspec describe Acme/Text/Markdown

will now put the spec in ``spec/MarkdownSpec.php`` and the class will be created
in  ``src/Markdown.php``.

Spec and source locations
-------------------------

The default locations used by **lopspec** for the spec files and source files
are `spec` and `src` respectively. You may find that this does not always suit
your needs. You can specify an alternative location in the configuration file.
You cannot do this at the command line as it does not make sense for a spec or
source files path to change at runtime.

You can specify alternative values depending on the namespace of the class you are
describing. In lopspec, you can group specification files by a certain namespace in a
*suite*. For each suite, you have several configuration settings:

* ``namespace`` - The namespace of the classes. Used for generating
  spec files, locating them and generating code;
* ``spec_prefix`` [**default**: ``spec``] - The namespace prefix for
  specifications. The complete namespace for specifications is
  ``%spec_prefix%\%namespace%``;
* ``src_path`` [**default**: ``src``] - The path to store the generated
  classes. Paths are relative to the location of the config file. **lopspec**
  creates the directories if they do not exist. This does not include the namespace
  directories;
* ``spec_path`` [**default**: ``.``] - The path of the specifications. This
  does not include the spec prefix or namespace.

Some examples:

.. code-block:: yaml

    suites:
        acme_suite:
            namespace: Acme\Text
            spec_prefix: acme_spec

        # shortcut for
        # my_suite:
        #     namespace: The\Namespace
        my_suite: The\Namespace

**lopspec** will use suite settings based on the namespaces.
If you have suites with different spec directories then ``lopspec run``
will run the specs from each of the directories using the relevant suite settings.

When you use ``lopspec desc`` **lopspec** creates the spec using the matching
configuration.  E.g. ``lopspec desc Acme/Text/MyClass`` will use the namespace
``acme_spec\Acme\Text\MyClass``.

If the namespace does not match one of the namespaces in the suites config then
**lopspec** uses the default settings. If you want to change the defaults then
you can add a suite without specifying the namespace.

.. code-block:: yaml

    suites:
        #...
        default:
            spec_prefix: acme_spec
            spec_path: acmes-specs
            src_path: acme-src

You can just set this suite if you wanted to override the default settings for
all namespaces. Since **lopspec** matches on namespaces you cannot specify more
than one set of configuration values for a null namespace. If you do add more
than one suite with a null namespace then **lopspec** will use the last one
defined.

Note that the default spec directory is ``.``, specs are created in the `spec`
directory because it is the first part of the spec namespace. This means that
changing the `spec_path` will result in additional directories before `spec` not
instead of it. For example, with the config:

.. code-block:: yaml

    suites:
        acme_suite:
            namespace: Acme\Text
            spec_prefix: acme_spec

running:

.. code-block:: bash

    $ bin/lopspec describe Acme/Text/Markdown

will create the spec in the file ``acme_spec/spec/Acme/Text/MarkdownSpec.php``

Formatter
---------

You can also set another default formatter instead of ``progress``. The
``--format`` option of the command can override this setting. To set the
formatter, use ``formatter.name``:

.. code-block:: yaml

    formatter.name: pretty

The formatters available by default are:

* progress (default)
* html/h
* pretty
* junit
* dot

More formatters can be added by :doc:`extensions</cookbook/extensions>`.

Options
-------

You can turn off code generation in your config file by setting ``code_generation``:

.. code-block:: yaml

    code_generation: false

You can also set your tests to stop on failure by setting ``stop_on_failure``:

.. code-block:: yaml

    stop_on_failure: true

Extensions
----------

To register lopspec extensions, use the ``extensions`` option. This is an
array of extension classes:

.. code-block:: yaml

    extensions:
        - LopSpec\Symfony2Extension\Extension
