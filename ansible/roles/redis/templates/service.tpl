[Unit]
Description=Redis In-Memory Data Store
After=network.target

[Service]
User=redis
Group=redis
ExecStart={{ redis_path }}/bin/redis-server /etc/redis/{{ redis.port }}.conf
ExecStop={{ redis_path }}/bin/redis-cli -p {{ redis.port }} shutdown
Restart=always

[Install]
WantedBy=multi-user.target
