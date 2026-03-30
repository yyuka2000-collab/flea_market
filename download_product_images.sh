#!/bin/bash
# 商品画像ダウンロードスクリプト
# 実行前に storage:link が完了していること
# 使い方: bash download_product_images.sh

BASE_URL="https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image"
DEST="storage/app/public/products"

mkdir -p "$DEST"

curl -L -o "$DEST/Armani+Mens+Clock.jpg"        "$BASE_URL/Armani+Mens+Clock.jpg"
curl -L -o "$DEST/HDD+Hard+Disk.jpg"             "$BASE_URL/HDD+Hard+Disk.jpg"
curl -L -o "$DEST/iLoveIMG+d.jpg"                "$BASE_URL/iLoveIMG+d.jpg"
curl -L -o "$DEST/Leather+Shoes+Product+Photo.jpg" "$BASE_URL/Leather+Shoes+Product+Photo.jpg"
curl -L -o "$DEST/Living+Room+Laptop.jpg"        "$BASE_URL/Living+Room+Laptop.jpg"
curl -L -o "$DEST/Music+Mic+4632231.jpg"         "$BASE_URL/Music+Mic+4632231.jpg"
curl -L -o "$DEST/Purse+fashion+pocket.jpg"      "$BASE_URL/Purse+fashion+pocket.jpg"
curl -L -o "$DEST/Tumbler+souvenir.jpg"          "$BASE_URL/Tumbler+souvenir.jpg"
curl -L -o "$DEST/Waitress+with+Coffee+Grinder.jpg" "$BASE_URL/Waitress+with+Coffee+Grinder.jpg"
curl -L -o "$DEST/外出メイクアップセット.jpg"    "$BASE_URL/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg"

echo "Done! Images saved to $DEST"