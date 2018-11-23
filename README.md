# Doctrine Watcher Bundle

A Symfony bundle for [bentools/doctrine-watcher](https://github.com/bpolaszek/doctrine-watcher).

## Installation

```bash
composer require bentools/doctrine-watcher:1.0.x-dev
```

## Configuration

There are 2 way to configure the bundle:

### Via bundle configuration

```yaml
# app/config/config.yml or config/packages/doctrine_watcher.yaml with Symfony Flex
doctrine_watcher:
    watch:
        App\Entity\Book:
            properties:
                title:
                    callback: 'App\Services\BookWatcher::onTitleChange'
                reviews:
                    callback: 'App\Services\BookWatcher::onReviewsChange'
                    iterable: true
```


### Via service tags

```yaml
# app/config/services.yml or config/services.yaml with Symfony Flex
services:
    App\Services\BookWatcher:
        tags:
            - { name: bentools.doctrine_watcher, entity_class: App\Entity\Book, property: 'title', method: 'onTitleChange' }
            - { name: bentools.doctrine_watcher, entity_class: App\Entity\Book, property: 'reviews', method: 'onReviewsChange', iterable: true }
```


## License

MIT.