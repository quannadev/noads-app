NoAds
==

Xem phim, youtube không bị quảng cáo.

## Cài đặt

### Backend 

```bash
go run main.go
```

- Run Backend search engine
```bash
docker compose up -d
```
### Frontend

home: http://localhost:8084

demo: http://noads.quanna.dev

#### Cài đặt wordpress plugin
  - Cài đặt và active wordpress plugin
    - search: wp_plugin/bootlink.zip
    - Bradmax Player

## Cách sử dụng

- Search phim:
  - Search đúng và đủ tên phim (tiếng việt có dấu và không dấu đều ok)
  - vd: "Về nhà đi con", "Cô gái đến từ hôm qua", "Vì sao đưa anh tới"
- Youtube:
  - Chỉ cần copy link youtube và paste vào ô search
  - vd: https://www.youtube.com/watch?v=3JZ_D3ELwOQ
- Kết quả:
  - Kết quả sẽ hiển thị ở dưới ô search
  - Click vào kết quả để xem phim hoặc video youtube
