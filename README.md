# Heffer

An application which allows uploads of a collection of images.  All images will be converted to PNG and thumbnails will be generated based on image entropy.

This project was largely an experiment in learning docker.

**Requirements**

 - [docker](https://www.docker.com/products/docker)

## Development

The first time this is run it will probably take like 5+ minutes to download and install everything so get a ☕.

```bash
ENV=development ./scripts/run.sh
```

The primary difference is that the development environment doesn't forward uploads to s3 for long term storage.

**tests:**

```bash
./scripts/tests.sh
```

**dependencies:**

If you just need dependencies for your IDE or reasons:

```bash
./scripts/composer.sh install
```

## Production

This is not really production ready but if you are a rebel you can run:

```bash
ENV=production ./scripts/run.sh
```

This will cause the application to upload files to s3 for long term storage. If you are using docker, you should configure the `aws` command line interface with the appropriate profile name and pass it in as a volume.  Also, when viewing a collection, urls are temporary (signed) urls that have a timeout of 5 minutes.  This 'feature' prevents others from hosting your collection of images via heffer.  This may also stop the image from showing up in search engines.

## Deployment

There is none at the moment.

## API

This is not implimented but it would look something like:

Upload files

```bash
curl -XPOST -F files=@[file] name=test localhost:3000
```

View collection

```bash
curl localhost:3000/uploads/[id]/[collection name]
```
