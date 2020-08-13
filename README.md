# docs.aw-studio.de

## Setup

```shell
git clone https://github.com/aw-studio/docs.git resources/docs
```

The `.env` needs:

-   `GITHUB_CLIENT_ID`
-   `GITHUB_CLIENT_SECRET`

```
# ...

GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=

# ...
```

Die variablen können auf [github.com/settings/applications/new](https://github.com/settings/applications/new) erstellt werden. `APP_URL` entspricht der **Homepage URL** und die **Authorization callback URL** ist `{APP_URL}/login/github/callback` also z.B.: `https://docs.aw-studio.de/login/github/callback`

## Update

```shell
cd resources/docs && git pull && cd ../..
```
