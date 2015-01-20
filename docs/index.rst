Quick Start
===========

Create a ``composer.json`` file:

.. code-block:: js

    {
        "require-dev": {
            "lopspec/lopspec": "~1.0"
        },
        "config": {
            "bin-dir": "bin"
        }
    }

Install **lopspec** with composer:

.. code-block:: bash

    curl http://getcomposer.org/installer | php
    php composer.phar install

Start writing specs:

.. code-block:: bash

    bin/lopspec desc Acme/Calculator

Learn more from :doc:`the documentation <manual/introduction>`.

.. toctree::
   :hidden:
   :maxdepth: 1

   manual/introduction
   manual/installation
   manual/getting-started
   manual/prophet-objects
   manual/let-and-letgo

.. toctree::
   :hidden:
   :maxdepth: 1

   cookbook/configuration
   cookbook/console
   cookbook/construction
   cookbook/matchers
   cookbook/templates
   cookbook/extensions
   cookbook/wrapped-objects
