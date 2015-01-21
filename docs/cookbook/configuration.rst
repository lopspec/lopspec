Configuration
=============

Lopspec looks for the following configuration files when ran which can override
its built-in defaults. It will look for the follow files:

* ``lopspec.dist.yml`` - In the root of your project.
* ``lopspec.yml`` - Also looked for in the root of your project.
* ``.lopspec.yml`` - Looked for in your home folder HOME (Unix) or USERPROFILE
  (Windows).

You can also use the ``-c`` or ``--config`` command line option to use an
additional custom file.

.. code-block:: bash

    $ bin/lopspec run --config path/to/different-lopspec.yml

Lopspec looks for the files in the order given with any settings found in the
later files overwriting earlier settings from any of the previous files.

.. _configuration-suites:

PSR-4
-----

**lopspec** assumes a PSR-4 mapping of namespaces to the src and spec
directories by default. So for example running:

.. code-block:: bash

    $ bin/lopspec describe Acme/Text/Markdown

Will create a spec in the ``spec/Text/MarkdownSpec.php`` file and the class will
be created in ``src/Text/Markdown.php``

The first part of the ``namespace`` option in a suite well be omitted from the
directory structure like so:

.. code-block:: yaml

    suites:
        acme_suite:
            namespace: Acme\Text

With this config running:

.. code-block:: bash

    $ bin/lopspec describe Acme/Text/Markdown

will now put the spec in ``spec/Acme/Text/MarkdownSpec.php`` and it contains
``namespace Spec\Acme\Text;``. The class will also be created in
``src/Text/Markdown.php`` and it contains ``namespace Acme\Text;``. If you need
to support a legacy PSR-0 directory structure that includes the first part of
the namespace simple add it to the end of the src and/or spec paths as needed.

Spec and source locations
-------------------------

The default locations used by **lopspec** for the spec files and source files
are `spec` and `src` respectively. You may find that this does not always suit
your needs. You can specify an alternative location in the configuration file.
You cannot do this at the command line as it does not make sense for a spec or
source files path to change at runtime.

You can specify alternative values depending on the namespace of the class you
are describing. In lopspec, you can group specification files by a certain
namespace in a *suite*. For each suite, you have several configuration settings:

* ``src_namespace`` [**default**: empty string] - The namespace of the classes.
  Used for locating and build the full path to classes and added to the
  ``spec_namespace`` as well.

* ``spec_namespace`` [**default**: ``Spec``] - The namespace prefix for
  specifications. The complete namespace for specifications is
  ``%spec_namespace%\%src_namespace%``.
* ``src_path`` [**default**: ``src``] - The path to store the generated
  classes. Paths are relative to the location of the config file. **lopspec**
  creates the directories if they do not exist. This does not include the
  namespace directories which are add as suffix when making paths.
* ``spec_path`` [**default**: ``spec``] - The path of the specifications. This
  does not include the spec prefix or namespace which are added as needed when
  building a full path.

Some examples:

.. code-block:: yaml

    suites:
        acme_suite:
            src_namespace: Acme\Text
            spec_namespace: Acme\Spec

        # shortcut for
        # my_suite:
        #     src_namespace: The\Namespace
        my_suite: The\Namespace

**lopspec** will use suite settings based on the namespaces.
If you have suites with different spec directories then ``lopspec run``
will run the specs from each of the directories using the relevant suite
settings.

When you use ``lopspec desc`` **lopspec** creates the spec using the matching
configuration.  E.g. ``lopspec desc Acme/Text/MyClass`` will use the namespace
``Acme\Spec\Acme\Text\MyClass``.

If the namespace does not match one of the namespaces in the suites config then
**lopspec** uses the default settings. If you want to change the defaults then
you can add a suite without specifying the namespace.

.. code-block:: yaml

    suites:
        #...
        default:
            spec_namespace: acme_spec
            spec_path: acmes-specs
            src_path: acme-src

You can just set this suite if you wanted to override the default settings for
all namespaces. Since **lopspec** matches on namespaces you cannot specify more
than one set of configuration values for a null namespace. If you do add more
than one suite with a null namespace then **lopspec** will use the last one
defined.

Next we'll look at some examples working with existing projects. First one is
an example with some non-default values on say a new project or one that is
already uses PSR-4 throughout.

.. code-block:: yaml

    suites:
        acme_suite:
            src_namespace: Acme\Text
            src_path: lib
            spec_namespace: acme_spec
            spec_path: tests

running:

.. code-block:: bash

    $ bin/lopspec describe Acme/Text/Markdown

will create the spec in the file ``tests/acme_spec/Acme/Text/MarkdownSpec.php``
containing ``namespace acme_spec\Acme\Text;``. The src file is in
``lib/Acme/Text/Markdown.php`` and ``namespace Acme\Text;``.

Next how to work with a legacy project that is still using PSR-0 type paths and
used **phpspec** before. Here is the existing ``phpspec.yml.dist`` file.

.. code-block:: yaml

    suites:
        acme_suite:
            namespace: Acme\Text
            src_path: lib
            spec_path: tests
            spec_prefix: acme_spec

and here would be the directory structure you might expect to see in the PSR-0
project.

.. code-block:: bash

    bin/
    lib/
        Acme/
            Text/
                Markdown.php
    tests/
        acme_spec/
            Acme/
                Text/

now for the ``lopspec.yml.dist`` to work with the exist structure.

.. code-block:: yaml

    suites:
        acme_suite:
            src_namespace: Acme\Text
            src_path: lib/Acme
            spec_namespace: acme_spec
            spec_path: tests/acme_spec

note that you simply need to add the first part of the namespace to the paths so
the file are expected in the correct directories and the namespaces in the
files match up.

One last example for projects that used PSR-4 with **phpspec** before and it's
``psr4_prefix`` setting.

.. code-block:: yaml

    suites:
        acme_suite:
            namespace: Acme\Text
            psr4_prefix: Acme
            src_path: lib
            spec_path: tests
            spec_prefix: acme_spec

the new ``lopspec.dist.yml`` would look something like the following.

.. code-block:: yaml

    suites:
        acme_suite:
            src_namespace: Acme\Text
            src_path: lib
            spec_namespace: acme_spec
            spec_path: tests/acme_spec

**lopspec** gets the src namespace right by default and just needs some help
finding the spec files with the legacy PSR-0 style paths.

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
