@echo off
rm -rf public
hugo
tar -zcvf files/public.tar.gz -C public .

