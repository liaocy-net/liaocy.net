# Common Configurations

```bash

# ssh to server
ssh ubuntu@35.73.227.145

############## mount share volume ############## 
# ref.: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ebs-using-volumes.html
sudo lsblk -f
sudo mkdir /data
sudo mount /dev/nvme1n1 /data
sudo blkid
sudo nano /etc/fstab
# UUID=06754bd0-0f44-46ad-a1f6-0fb99167f0df  /data  xfs  defaults,nofail  0  2
## Test Auto Mount
sudo umount /data
sudo mount -a
## reboot
sudo reboot
## Test Auto Mount
tail /data/test.txt

############## install docker ##############
# ref.: https://docs.docker.com/engine/install/ubuntu/
sudo apt-get update
sudo apt-get install ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

sudo apt-get update
sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
sudo systemctl status docker.service
sudo systemctl enable docker.service

```

## Mount Share Volume on AWS 

[URL](https://ap-northeast-1.console.aws.amazon.com/ec2/home?region=ap-northeast-1#Volumes:)

![Share Volume](imgs/config_server/iShot_2023-07-08_13.21.03.png "")

# Web Server

```bash
##################### nginx ######################
sudo apt install nginx
sudo systemctl status nginx
sudo systemctl enable nginx
sudo nano /etc/nginx/sites-available/charing.biz #ref: charing.biz section
sudo ln -s /etc/nginx/sites-available/charing.biz /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx


##################### certbot ######################


##################### crontab ######################
sudo crontab -e
# * * * * * sudo docker exec $(sudo docker ps -a -q --filter="name=prd-charing-web") php artisan schedule:run >> /dev/null 2>&1
```

## charing.biz
```
server {
    listen        80;
    server_name   charing.biz;
    location / {
        proxy_pass         http://localhost:8000;
        proxy_http_version 1.1;
        proxy_set_header   Upgrade $http_upgrade;
        proxy_set_header   Connection keep-alive;
        proxy_set_header   Host $host;
        proxy_cache_bypass $http_upgrade;
        proxy_set_header   X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header   X-Forwarded-Proto $scheme;
    }
}
```