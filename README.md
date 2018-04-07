# bricks
基于swoole httpserver 的小型框架。练习 swoole

# 使用说明
- 依赖说明：
    - PHP 版本 5 以上
    - swoole 版本 >= 1.9.5
- 启动服务：
    git clone 下载下来,进入 bin 目录, start 启动,stop 停止, restart 重启

    ```shell
    ./httpd.php start
    ```
    直接访问 127.0.0.1:9501,如果出现 hello world 表示成功。
- Nginx 代理配置：
  ```
  server {
      server_name www.test.com;
      listen 80;
      index index.html;

      location = / {
          rewrite ^(.*)$ /index last;
      }

      location / {
          proxy_http_version 1.1;
          proxy_set_header Connection "keep-alive";
          proxy_set_header Host $http_host;
          proxy_set_header X-Real-IP $remote_addr;
          if (!-e $request_filename) {
               proxy_pass http://127.0.0.1:9501;
          }
      }
  }
  ```

# 其他
- 有兴趣的可以研究，欢迎使用反馈。
