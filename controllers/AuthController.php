<?php
require_once BASE_PATH.'/core/Controller.php';

class AuthController extends Controller {

    public function index(): void { $this->login(); }

    public function login(): void {
        if ($this->isLoggedIn()) {
            if ($this->isAdmin()) $this->redirect(BASE_URL.'/index.php?page=admin');
            else $this->redirect(BASE_URL.'/index.php');
        }
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = Security::sanitize($_POST['email'] ?? '');
            $pass  = $_POST['password'] ?? '';
            $pdo   = getPDO();
            $stmt  = $pdo->prepare("SELECT * FROM utilisateurs WHERE email=? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user && password_verify($pass, $user['mot_de_passe'])) {
                $pdo->prepare("UPDATE utilisateurs SET last_login=NOW(), login_attempts=0 WHERE id=?")->execute([$user['id']]);
                $_SESSION['user'] = ['id'=>$user['id'],'nom'=>$user['nom'],'prenom'=>$user['prenom'],'email'=>$user['email'],'role'=>$user['role']];
                if ($user['role'] === 'admin') $this->redirect(BASE_URL.'/index.php?page=admin');
                else $this->redirect(BASE_URL.'/index.php');
            } else {
                if ($user) $pdo->prepare("UPDATE utilisateurs SET login_attempts=login_attempts+1 WHERE id=?")->execute([$user['id']]);
                $error = 'Email ou mot de passe incorrect.';
            }
        }
        $this->render('auth/login', ['error'=>$error, 'pageTitle'=>'Connexion — PharmaLink']);
    }

    public function register(): void {
        $error = ''; $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom    = Security::sanitize($_POST['nom']    ?? '');
            $prenom = Security::sanitize($_POST['prenom'] ?? '');
            $email  = Security::sanitize($_POST['email']  ?? '');
            $pass   = $_POST['password']  ?? '';
            $pass2  = $_POST['password2'] ?? '';
            if (!$nom || !$prenom || !$email || !$pass) $error = 'Tous les champs sont obligatoires.';
            elseif ($pass !== $pass2) $error = 'Les mots de passe ne correspondent pas.';
            elseif (strlen($pass) < 6) $error = 'Mot de passe trop court (min. 6 caractères).';
            else {
                $pdo  = getPDO();
                $chk  = $pdo->prepare("SELECT id FROM utilisateurs WHERE email=? LIMIT 1");
                $chk->execute([$email]);
                if ($chk->fetch()) $error = 'Cet email est déjà utilisé.';
                else {
                    $hash = password_hash($pass, PASSWORD_BCRYPT);
                    $pdo->prepare("INSERT INTO utilisateurs (nom,prenom,email,mot_de_passe,role,email_verified,created_at) VALUES (?,?,?,?,'client',1,NOW())")->execute([$nom,$prenom,$email,$hash]);
                    $success = 'Compte créé avec succès ! Connectez-vous.';
                }
            }
        }
        $this->render('auth/register', ['error'=>$error,'success'=>$success,'pageTitle'=>'Inscription — PharmaLink']);
    }

    public function logout(): void {
        session_destroy();
        header('Location: '.BASE_URL.'/index.php');
        exit;
    }

    public function forgot_password(): void {
        $this->render('auth/forgot_password', ['pageTitle'=>'Mot de passe oublié']);
    }

    public function reset_password(): void {
        $this->render('auth/reset_password', ['pageTitle'=>'Réinitialisation']);
    }
}
