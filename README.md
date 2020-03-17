### Traefik/Nextcloud/Collabora with Docker Compose

This repo contains the config files to stand up an instance of Traefik for reverse proxying onto Nextcloud and OnlyOffice Document Server. All of the docker-compose files in this repo could easily be combined into one - they are seperated only for ease of reading and in the odd cases where not all of the services may be deployed at once.

Traefik will be automatically configured to redirect all domains to HTTPs, and all certs will be automatically created with LetsEncrypt (provided the relevant DNS records are in place).

Additionally, `containrrr/watchtower` can be deployed to automatically update containers as they become available.

This repo assumes the following directory structure is present on the deployment host:

```bash
/opt
  /services
    /config       # This repo!
      /onlyoffice
      /nextcloud
      /traefik
      /pastebin
    /state
      /onlyoffice
      /nextcloud
      /traefik
```

#### Deploy Traefik

Deploying Traefik is relatively simple in this case. First, we need to generate some creds for the dashboard (if it'll be used). The following command will generate the creds in the right form (`$` must be double escaped):

```
$ echo $(htpasswd -nb user password) | sed -e s/\\$/\\$\\$/g
```

Once you have the creds, update them in `traefik/docker-compose.yml` Also be sure to adjust for the correct domains in the config file

```bash
$ cd traefik/
$ docker-compose up -d
```

#### Deploy Nextcloud

As above, be sure to replace the domain names in the `nextcloud/docker-compose.yml` file before attempting to deploy

```bash
$ cd nextcloud/
$ docker-compose up -d
# Wait for the nextcloud login to be available, can take some time!
# Track with docker-compose logs -f
$ docker-compose exec -u www-data nc-app /bin/bash -c '/var/www/html/occ config:system:set overwriteprotocol --value "https"'
```

As an aside, the background jobs normally executed by nextcloud with cron will not work by default with this setup. One way around this is to setup a cronjob on the _host_ machines as follows:

```
*/10 * * * * docker exec -u www-data nc-app php -f /var/www/html/cron.php
```

You may also need to define a trusted proxy in the nextcloud `config.php` which will be found in `<state_dir>/nextcloud/app/config/config.php`. Trusted proxies are defined like so:

```
//...
'trusted_proxies' => ['172.20.0.4'],
//...
```

#### Deploy OnlyOffice

As above, be sure to replace all instances of the domain names in the `collabora/docker-compose.yml` file before deploying.

```bash
$ cd onlyoffice/
$ docker-compose up -d
```

NextCloud will then need to have the Collabora Online app and installed, and endpoint set as per your config.

#### Deploy Pastebin

As above, be sure to replace all instances of the domain names in the `pastebin/docker-compose.yml` file before deploying.

```bash
$ cd pastebin/
$ docker-compose up -d
```

#### Deploy Watchtower

Watchtower will check on a schedule (02:00 every day) for new containers and automatically update them, removing any old container images from the host.

```bash
$ cd watchtower/
$ docker-compose up -d
```
