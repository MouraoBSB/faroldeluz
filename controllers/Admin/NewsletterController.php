<?php
/**
 * AdminNewsletterController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17 02:28:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/NewsletterSubscriber.php';

class AdminNewsletterController extends Controller {
    private $newsletterModel;
    
    public function __construct() {
        $this->newsletterModel = new NewsletterSubscriber();
    }
    
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
        $status = isset($_GET['status']) ? sanitize_input($_GET['status']) : '';
        $perPage = 20;
        
        $conditions = [];
        if ($status) {
            $conditions['status'] = $status;
        }
        
        if ($search) {
            $offset = ($page - 1) * $perPage;
            $whereSQL = $status ? "WHERE status = ? AND (name LIKE ? OR email LIKE ?)" : "WHERE name LIKE ? OR email LIKE ?";
            $params = $status ? [$status, "%{$search}%", "%{$search}%"] : ["%{$search}%", "%{$search}%"];
            
            $countSQL = "SELECT COUNT(*) as total FROM newsletter_subscribers {$whereSQL}";
            $stmt = $this->newsletterModel->db->prepare($countSQL);
            $stmt->execute($params);
            $total = $stmt->fetch()['total'];
            
            $dataSQL = "SELECT * FROM newsletter_subscribers {$whereSQL} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}";
            $stmt = $this->newsletterModel->db->prepare($dataSQL);
            $stmt->execute($params);
            $subscribers = $stmt->fetchAll();
            
            $totalPages = ceil($total / $perPage);
        } else {
            $result = $this->newsletterModel->paginate($page, $perPage, $conditions, 'created_at DESC');
            $subscribers = $result['data'];
            $total = $result['total'];
            $totalPages = $result['last_page'];
        }
        
        $stats = [
            'total' => $this->newsletterModel->count(),
            'active' => $this->newsletterModel->count(['status' => 'active']),
            'unsubscribed' => $this->newsletterModel->count(['status' => 'unsubscribed'])
        ];
        
        $this->view('admin/newsletter/index', [
            'subscribers' => $subscribers,
            'stats' => $stats,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_items' => $total
            ],
            'search' => $search,
            'status' => $status
        ]);
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/newsletter'));
            return;
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($id && $this->newsletterModel->delete($id)) {
            $_SESSION['success'] = 'Inscrito removido com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao remover inscrito.';
        }
        
        $this->redirect(base_url('admin/newsletter'));
    }
    
    public function export() {
        $status = isset($_GET['status']) ? sanitize_input($_GET['status']) : 'active';
        
        $conditions = ['status' => $status];
        $subscribers = $this->newsletterModel->where($conditions, 'created_at DESC');
        
        ob_clean();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="newsletter_' . $status . '_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Nome', 'E-mail', 'Status', 'Data de Inscrição'], ',', '"', '\\');
        
        foreach ($subscribers as $subscriber) {
            fputcsv($output, [
                $subscriber['name'],
                $subscriber['email'],
                $subscriber['status'],
                date('d/m/Y H:i', strtotime($subscriber['created_at']))
            ], ',', '"', '\\');
        }
        
        fclose($output);
        exit;
    }
}
