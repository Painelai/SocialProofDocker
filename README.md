# Social Proof Engine — Deploy Render

## Variáveis de ambiente (opcional — já tem defaults)

| Variável | Valor |
|----------|-------|
| DB_HOST  | 91j0qi.h.filess.io |
| DB_PORT  | 61032 |
| DB_NAME  | Db_SocialProof_partlygone |
| DB_USER  | Db_SocialProof_partlygone |
| DB_PASS  | 3d1c730e00a2e917943ab2f1d6814a2b2d75cd4a |

## Deploy no Render

1. Crie um novo **Web Service** no Render
2. Conecte o repositório GitHub com este projeto
3. Runtime: **Docker**
4. Port: **80**
5. Clique em **Deploy**

## URLs após deploy

- Widget: `https://seu-app.onrender.com/widget/index.php?room=dieta-faraonica`
- Admin:  `https://seu-app.onrender.com/admin/index.php`
- API:    `https://seu-app.onrender.com/api/index.php?path=chat/messages&room=dieta-faraonica`
