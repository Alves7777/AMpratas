# AMpratas — Loja de Pratas

E-commerce de joias em prata 925 com vitrine inspirada em Vivara / Pandora / Rommanel, painel do lojista em **Laravel + Filament**, **MySQL** e fotos no **MinIO** (o MinIO que você já tem em `Documents/miniio`).

## Por que Laravel + Filament (em vez de WordPress)

- Painel moderno para o dono cadastrar peças, preços e fotos
- Integração nativa com MinIO (S3)
- Frontend 100% customizado no visual da marca AMpratas

## Arquitetura

`Controller → Form Request → Service → Repository → Model`

Clean Architecture + MVC, DI, SOLID, DTOs tipados. Detalhes: [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md).

## Subir

Pré-requisito: MinIO já rodando (`minio-704apps` na rede `miniio_minionet`).

```bash
cd ~/Documents/AMpratas
docker compose up -d --build
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
docker compose exec app php artisan minio:ensure-bucket
```

Assets (já buildados neste projeto):

```bash
# se precisar rebuildar CSS/JS
source ~/.nvm/nvm.sh && nvm use 22
npm install && npm run build
```

## URLs

| Serviço | URL |
|---------|-----|
| Loja | http://localhost:8090 |
| Admin (lojista) | http://localhost:8090/admin |
| MinIO Console | http://localhost:9001 |
| MySQL | `127.0.0.1:3311` |

## Paralelo WordPress + WooCommerce

Há um segundo projeto em `../AMpratas-wp` para comparar:

| Stack | URL |
|-------|-----|
| Laravel + Filament | http://localhost:8090 |
| WordPress + WooCommerce | http://localhost:8091 |


## Login do admin

- E-mail: `admin@ampratas.com`
- Senha: `ampratas123`

## MinIO

Usa o MinIO existente (`Documents/miniio`):

- User/Pass: `change_me` / `change_me` (do `.env` do miniio)
- Bucket: `ampratas`
- Endpoint interno do app: `http://minio:9000`

Para o admin salvar fotos no MinIO, no `.env` / compose:

```env
FILESYSTEM_DISK=s3
MEDIA_DISK=s3
```

Por padrão a loja usa disco `public` (fotos seed em `/public/images`).

## O que o lojista faz no `/admin`

- Categorias (Alianças, Anéis, Colares…)
- Produtos (preço, estoque, galeria, destaque)
- Pedidos (status: pendente → confirmado → enviado…)

Checkout da loja gera pedido + link WhatsApp.

## Referências de UX

- [Vivara](https://www.vivara.com.br/)
- [Rommanel](https://www.rommanel.com.br/)
- [Pandora](https://br.pandora.net/)
