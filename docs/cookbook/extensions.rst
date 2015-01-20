Extensions
==========

Extensions can add functionality to **lopspec**, such as, integration with
a particular framework. See below for some example extensions.

Installation
------------

Individual extensions will have their own documentation that you can follow.
Usually you can install an extension by adding it to your ``composer.json``
file and updating your vendors.

Configuration
-------------

You will need to tell **lopspec** that you want to use the extension. You
can do this by adding it to the config file:

.. code-block:: yaml

    extensions:
        - MageTest\LopSpec\MagentoExtension\Extension

You can pass options to the extension as well:

.. code-block:: yaml

    extensions:
        - MageTest\LopSpec\MagentoExtension\Extension
    mage_locator:
        spec_prefix: spec
        src_path: public/app/code
        spec_path: spec/public/app/code
        code_pool: community

See the :doc:`Configuration Cookbook </cookbook/configuration>` for more about config files.

Example extensions
------------------

Framework Integration
~~~~~~~~~~~~~~~~~~~~~

 * `Symfony2 <https://github.com/lopspec/Symfony2Extension>`_
 * `Magento <https://github.com/MageTest/MageSpec>`_
 * `Laravel <https://github.com/BenConstable/lopspec-laravel>`_ (phpspec)

Code generation
~~~~~~~~~~~~~~~

 * `Typehinted Methods <https://github.com/ciaranmcnulty/lopspec-typehintedmethods>`_ (phpspec)
 * `Example Generation <https://github.com/richardmiller/ExemplifyExtension>`_ (phpspec)

Additional Formatters
~~~~~~~~~~~~~~~~~~~~~

 * `Nyan Formatters <https://github.com/lopspec/nyan-formatters>`_

Metrics
~~~~~~~

 * `Code coverage <https://github.com/henrikbjorn/PhpSpecCodeCoverageExtension>`_ (phpspec)

Miscellaneous
~~~~~~~~~~~~~

 * `Prepare <https://github.com/coduo/phpspec-prepare-extension>`_ (phpspec)
 * `Data provider <https://github.com/coduo/phpspec-data-provider-extension>`_ (phpspec)
 * `Matcher extension <https://github.com/coduo/phpspec-matcher-extension>`_ (phpspec)
 * `Behat Integration <https://github.com/richardmiller/BehatSpec>`_ (phpspec)
 * `Example skipping through annotation <https://github.com/akeneo/PhpSpecSkipExampleExtension>`_ (phpspec)
