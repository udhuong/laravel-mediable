Configuration
========================

.. highlight:: php


.. _disks:

Disks
------------------------
Laravel-Mediable is built on top of Laravel's Filesystem component. Before you use the package, you will need to configure the filesystem disks where you would like files to be stored in ``config/filesystems.php``. `Learn more about filesystem disk <https://laravel.com/docs/5.2/filesystem>`_.

::

    <?php
    //...
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
            'url' => 'https://example.com/storage/app',
            'visibility' => 'public'
        ],

        'uploads' => [
            'driver' => 'local',
            'root'   => public_path('uploads'),
            'url' => 'https://example.com/uploads',
            'visibility' => 'public'
        ],
    ]
    //...


Once you have set up as many disks as you need, edit ``config/mediable.php`` to authorize the package to use the disks you have created.

::

    <?php
    //...
    /*
     * Filesystem disk to use if none is specified
     */
    'default_disk' => 'uploads',

    /*
     * Filesystems that can be used for media storage
     */
    'allowed_disks' => [
        'local',
        'uploads',
    ],
    //...


.. _validation:

Validation
------------------------

The `config/mediable.php` offers a number of options for configuring how media uploads are validated. These values serve as defaults, which can be overridden on a case-by-case basis for each ``MediaUploader`` instance.

::

    <?php
    //...
    /*
     * The maximum file size in bytes for a single uploaded file
     */
    'max_size' => 1024 * 1024 * 10,

    /*
     * What to do if a duplicate file is uploaded. Options include:
     *
     * * 'increment': the new file's name is given an incrementing suffix
     * * 'replace' : the old file and media model is deleted
     * * 'error': an Exception is thrown
     *
     */
    'on_duplicate' => UdHuong\Mediable\MediaUploader::ON_DUPLICATE_INCREMENT,

    /*
     * Reject files unless both their mime and extension are recognized and both match a single aggregate type
     */
    'strict_type_checking' => false,

    /*
     * Reject files whose mime type or extension is not recognized
     * if true, files will be given a type of `'other'`
     */
    'allow_unrecognized_types' => false,

    /*
     * Only allow files with specific MIME type(s) to be uploaded
     */
    'allowed_mime_types' => [],

    /*
     * Only allow files with specific file extension(s) to be uploaded
     */
    'allowed_extensions' => [],

    /*
     * Only allow files matching specific aggregate type(s) to be uploaded
     */
    'allowed_aggregate_types' => [],
    //...

.. _aggregate_types:

Aggregate Types
------------------------

Laravel-Mediable provides functionality for handling multiple kinds of files under a shared aggregate type. This is intended to make it easy to find similar media without needing to constantly juggle multiple MIME types or file extensions.

The package defines a number of common file types in the config file (``config/mediable.php``). Feel free to modify the default types provided by the package or add your own. Each aggregate type requires a key used to identify the type and a list of MIME types and file extensions that should be recognized as belonging to that aggregate type. For example, if you wanted to add an aggregate type for different types of markup, you could do the following.

::

    <?php
    //...
    'aggregate_types' => [
        //...
        'markup' => [
            'mime_types' => [
                'text/markdown',
                'text/html',
                'text/xml',
                'application/xml',
                'application/xhtml+xml',
            ],
            'extensions' => [
                'md',
                'html',
                'htm',
                'xhtml',
                'xml'
            ]
        ],
        //...
    ]
    //...


Note: a MIME type or extension could be present in more than one aggregate type's definitions (the system will try to find the best match), but each Media record can only have one aggregate type.

.. _extending_functionality:

Extending functionality
------------------------

The ``config/mediable.php`` file lets you specify a number of classes to be use for internal behaviour. This is to allow for extending some of the the default classes used by the package or to cover additional use cases.

::

    <?php
    /*
     * FQCN of the model to use for media
     *
     * Should extend UdHuong\Mediable\Media::class
     */
    'model' => UdHuong\Mediable\Media::class,

    /*
     * List of adapters to use for various source inputs
     *
     * Adapters can map either to a class or a pattern (regex)
     */
    'source_adapters' => [
        'class' => [
            Symfony\Component\HttpFoundation\File\UploadedFile::class => UdHuong\Mediable\SourceAdapters\UploadedFileAdapter::class,
            Symfony\Component\HttpFoundation\File\File::class => UdHuong\Mediable\SourceAdapters\FileAdapter::class,
            Psr\Http\Message\StreamInterface::class => UdHuong\Mediable\SourceAdapters\StreamAdapter::class,
        ],
        'pattern' => [
            '^https?://' => UdHuong\Mediable\SourceAdapters\RemoteUrlAdapter::class,
            '^/' => UdHuong\Mediable\SourceAdapters\LocalPathAdapter::class
        ],
    ],

    /*
     * List of URL Generators to use for handling various filesystem disks
     */
    'url_generators' => [
        'local' => UdHuong\Mediable\UrlGenerators\LocalUrlGenerator::class,
        's3' => UdHuong\Mediable\UrlGenerators\S3UrlGenerator::class,
    ],
