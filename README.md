# PUG Website --- now your website is faster than ever before
Fastest website engine for any framework with hard cache for rendered code. 

## Installation
Clone repository to your host folder, htdocs/public MUST be assigned as public_html folder.

Create folder htdocs/cache `mkdir cache && chmod 0777 cache`

Run command `yarn install`

To compile javascript run `yarn build`
To compile javascript run with minifying function `yarn build:min`

To compile sass files run `yarn sass`

To build a complete release run `yarn release`