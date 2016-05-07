### HOW TO INSTALL

1) Download this repository, set nginx, virtualhosts etc.
2) Run `composer install`
3) Set up database `/install/dump.sql`
4) Set `/app/config/config.local.neon` example:

```yaml
doctrine:
	host: 127.0.0.1
	user: root
	password: root
	dbname: teddy
```

5a) If you want WebSockets set `wss.domain.tld`, see config at https://github.com/grez/websockets/README.md
5b) If you don't want to use WebSockets delete `"teddy/websockets": "dev-master",` from `composer.json` and `websockets` from `app/config/config.neon`
6) You may now login with username `admin` and password `admin`

## How to disable Map

Delete `teddy/map` from `composer.json`, Map from `/app/config/config.neon` and `app/GameModule/presenters/MainPresenter.php`
