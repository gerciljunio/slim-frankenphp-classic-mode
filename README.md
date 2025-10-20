# Slim 4 + FrankenPHP (Classic Mode) + Docker
Boilerplate minimalista para desenvolvimento de APIs em PHP com **Slim Framework 4** rodando no **FrankenPHP** em **modo clássico**, ideal para desenvolvimento local com recarregamento automático a cada requisição (estilo o padrão PHP-FPM).

## 🚀 Tecnologias Utilizadas

- **PHP 8+**
- **Slim Framework 4** — microframework para APIs rápidas e leves  
- **FrankenPHP** — servidor PHP moderno com suporte a HTTP/3, TLS nativo e modo worker  
- **Docker & Docker Compose** — ambiente isolado e reproduzível  

## ⚙️ Excutar o projeto

### 1. Instale as dependências PHP localmente
```bash
composer install
```

### 2. Suba o container (modo worker)
```bash
docker compose up --build
```

> O servidor estará disponível em:   
> 👉 http://localhost:8081

### 3. Teste rápido
```bash
curl http://localhost:8081/ping
```
#### Resposta esperada
```json
{"message":"pong"}
```

## Estrutura de diretórios e arquivos

```
├── DockerfileContainer
├── DockerfileLocal
├── composer.json
├── docker-compose.yml
├── public
│   └── index.php
└── src
    ├── Controllers
    │   └── PingController.php
    ├── Exceptions
    │   └── Handler.php
    ├── Support
    │   └── helpers.php
    └── routes.php
```

## Descrição dos diretórios e pastas
**DockerfileLocal**  
Imagem de desenvolvimento. Não roda `composer` no build — o código e o `vendor/` são montados via bind mount. Ideal para editar e ver o resultado rapidamente.

**DockerfileContainer**  
Imagem “fechada” (multi-stage) para testes/staging/produção. Roda `composer install` dentro do build e copia `vendor/` para a imagem final (não depende do `vendor` do host).

**composer.json**  
Dependências PHP, autoload PSR-4 e inclusão de arquivos auxiliares (ex.: `src/Support/helpers.php`). Execute `composer install` no host (modo local) ou deixe para o build (modo container).

**docker-compose.yml**  
Orquestra o serviço `app` com FrankenPHP (porta, volumes, variáveis). Alterne entre DockerfileLocal e DockerfileContainer de acordo com sua escolha.

**public/index.php**  
Front controller do Slim. Inicializa o app, registra middlewares e o handler global de erros (JSON), inclui as rotas (`src/routes.php`) e executa em modo clássico ou worker (detectado pelo FrankenPHP).

**src/Controllers/PingController.php**  
Controlador de exemplo (rota `GET /ping`). Usa o helper JSON para responder de forma padronizada.

**src/Exceptions/Handler.php**  
Ponto central para personalizar tratamento de erros/exceções (mapear mensagens, códigos HTTP, payload JSON). Referenciado pelo middleware de erros no `index.php`.

**src/Support/helpers.php**  
Funções auxiliares globais carregadas via autoload de `files` (ex.: `toResponse($response, $data, $status)`), para padronizar respostas e evitar repetição.

**src/routes.php**  
Declaração das rotas do Slim (ex.: `GET /ping`). Mantém o `index.php` mais limpo e facilita evolução do roteamento.

## 🧠 Observações
- O modo clássico recompila o PHP a cada requisição, ideal para desenvolvimento local (auto-reload sem watcher).
- Ao modificar o código, basta recarregar o navegador ou repetir a requisição para ver as alterações.

## Autor
Gercil Junio - Desenvolvedor Backend

- [📧 Gmail](mailto:gerciljunio@gmail.com)
- [💼 LinkedIn](https://www.linkedin.com/in/gercil)
- [🐙 GitHub](https://github.com/gerciljunio)