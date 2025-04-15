✅ 1. Definir o Conceito do Site

Cria uma ideia clara para o site:

    Nome: Algo como EduFreela, TutorLink, StudyConnect…

    Missão: Plataforma onde alunos podem contratar freelancers para apoio escolar (explicações, correções, resumos…).

    Tipos de utilizadores:

        Estudantes (clientes)

        Professores/explicadores (freelancers)

        Admins (gestão da plataforma)

🧠 2. Planeamento Inicial
Mockups (sem código ainda)

    Faz esboços (à mão ou no Figma) das páginas principais:

        Página inicial

        Página de login/registro

        Página de perfil

        Página de listagem de serviços (ex: explicações de Matemática)

        Página de detalhe do serviço

        Página de contacto entre utilizadores

        Área de admin

Diagrama de Navegação

Mostra como o utilizador navega entre páginas:

Home → Login/Register → Perfil → Listar Serviços → Detalhe → Contratar/Contactar → Checkout → Review

🗃️ 3. Modelar a Base de Dados

Segue o plano da cadeira e começa com:

users (id, name, username, email, password_hash, is_admin)
services (id, title, description, price, delivery_time, category_id, freelancer_id)
categories (id, name)
transactions (id, client_id, service_id, status, created_at)
messages (id, sender_id, receiver_id, content, created_at)
reviews (id, service_id, client_id, rating, comment, created_at)

✏️ Depois podes adicionar:

    schedule para agendamentos de aulas

    documents para envio de PDFs/resumos

    flags para disputas ou reports

💻 4. Começar a Codar

Segue esta ordem para evitar confusões:
✅ Base:

    Setup do repositório git

    Criação da base de dados SQLite + script de criação

    Estrutura de pastas:

/css
/js
/img
/templates
/database

✅ Backend (PHP com PDO):

    Login / Logout

    Registro

    Perfil (editar nome/email/password)

    Listar e gerir serviços (para freelancers)

    Navegar e contratar serviços (para estudantes)

    Admin painel (gerir utilizadores, categorias)

✅ Frontend:

    HTML + CSS básico e responsivo (sem frameworks!)

    JS para:

        Filtros de pesquisa

        Live search (Ajax)

        Chat/inbox (podes usar polling simples)

✨ 5. Funcionalidades Únicas (opcional, mas valem nota!)

    🎓 Filtrar por disciplina (Matemática, Física, Inglês…)

    📆 Agendar aulas com escolha de dia/hora

    📩 Mensagens assíncronas

    📈 Dashboard de desempenho para freelancers

    ⭐ Reviews com comentários

    📁 Upload de resumos/documentos

🔐 6. Segurança

Implementa:

    Prepared statements com PDO ✅

    Sanitização de inputs (XSS) ✅

    CSRF tokens nos formulários ✅

    Hash de passwords com password_hash() ✅

🧪 7. Testar & Melhorar

    Testar com diferentes tipos de utilizadores

    Garantir que funciona bem em mobile

    Validar campos (JS + PHP)

    Criar um pequeno tutorial de como usar a plataforma

