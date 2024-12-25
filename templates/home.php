<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MVGR-COLLEGE</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .container {
            max-width: 500px;
            width: 100%;
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
        }

        button {
            height: 50px;
            width : 100%;
            border-radius: 5px;
            border: none;
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            color: #fff;
        }

        button:hover {
            background: linear-gradient(-135deg, #71b7e6, #9b59b6);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to College ERP</h1>
        <div class="button-container">
            <a href="?page=login">
                <button>Login</button>
            </a>
            <a href="?page=create_user">
                <button>Create Account</button>
            </a>
        </div>
    </div>
</body>
</html>
