# znclogs

A ZNC logs viewer for the web. Easily browse and read logs stored by ZNC.

The code is old and a bit hacky. Use it on your own risk and feel free to submit patches.

## Setup

Update `config.php`.

## Nginx rewrite rules

```nginx
location ^~ /znclogs/ {
	rewrite  ^/znclogs(/)?([^/]+)?(/)?([^/]+)?(/)?([^/]+)?(/)?$ /znclogs/index.php?network=$2&channel=$4&date=$6 last;
}
```
