#!/bin/bash

echo "Removing everything inside color-variants"
rm -rf ../color-variants/*
echo "Removed"

echo "Extracting zip in color-variants"
tar -zxf ../public.tar.gz --directory ../color-variants

rm ../public.tar.gz