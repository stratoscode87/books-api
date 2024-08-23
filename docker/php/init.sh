#!/bin/bash
exec supervisord --nodaemon --configuration /etc/supervisor/conf.d/supervisord.conf
