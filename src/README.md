# Cart Service

Bu proje, alışveriş sepeti yönetimi için geliştirilmiş bir mikroservis uygulamasıdır.

## 🚀 Kurulum

### Ön Gereksinimler

- Docker
- Docker Compose

### Kurulum Adımları

1. Projeyi klonlayın:
```bash
git clone https://github.com/my-microservice-project/cart-service
cd cart-service
```

2. Ortam değişkenlerini ayarlayın:
```bash
cp .env.example .env
```
`.env` dosyasını kendi ortamınıza göre düzenleyin.

3. Docker konteynerlerini başlatın:
```bash
docker-compose up -d
```

## 🛠 Servisler

Proje aşağıdaki servisleri içermektedir:

### 1. Webserver (Nginx)
- Container Adı: `webserver_[APP_NAME]`
- Port: Env dosyasında belirtilen `WEBSERVICE_PORT` üzerinden erişilebilir
- Alpine tabanlı Nginx sunucusu
- Statik dosya sunumu ve PHP-FPM proxy görevi görür

### 2. PHP-FPM
- Container Adı: `phpserver_[APP_NAME]`
- PHP 8.3 versiyonu
- Özel PHP konfigürasyonları ile birlikte gelir
- Uygulama kodunu çalıştıran ana servis

## 🌐 Ağ Yapılandırması

Tüm servisler `shared_network` adlı bir Docker bridge network üzerinde çalışır, bu sayede servisler birbirleriyle güvenli bir şekilde iletişim kurabilir.

## 📁 Proje Yapısı

```
cart-service/
├── docker/                 # Docker konfigürasyon dosyaları
│   ├── nginx/             # Nginx konfigürasyonları
│   └── php-fpm/           # PHP-FPM konfigürasyonları
├── src/                   # Kaynak kodlar
├── .env.example          # Örnek çevre değişkenleri
├── .env                  # Çevre değişkenleri
└── docker-compose.yml    # Docker compose konfigürasyonu
```