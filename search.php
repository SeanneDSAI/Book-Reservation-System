<?php include 'check_auth.php'; ?>
<?php include 'header.php'; ?>

<!-- Success and Error Messages -->
<?php
if (isset($_GET['success'])) {
    $success = $_GET['success'];
    echo '<div style="color: green; margin-bottom: 1rem; padding: 0.5rem; background: #d4edda; border: 1px solid #c3e6cb;">';
    if ($success === 'reserved') {
        $title = isset($_GET['title']) ? urldecode($_GET['title']) : 'the book';
        echo "Successfully reserved: <strong>$title</strong>!";
    }
    echo '</div>';
}

if (isset($_GET['error'])) {
    $error = $_GET['error'];
    echo '<div style="color: red; margin-bottom: 1rem; padding: 0.5rem; background: #f8d7da; border: 1px solid #f5c6cb;">';
    if ($error === 'already_reserved') {
        echo 'This book is already reserved by another user.';
    } elseif ($error === 'reservation_failed') {
        echo 'Reservation failed. Please try again.';
    } elseif ($error === 'book_not_found') {
        echo 'Book not found.';
    } elseif ($error === 'already_reserved_by_you') {
        echo 'You have already reserved this book.';
    }
    echo '</div>';
}
?>

<h2>Search Books</h2>

<!-- Search Form -->
<form method="GET" action="search.php">
    <div style="margin-bottom: 1rem;">
        <label>Search by Title/Author:</label><br>
        <input type="text" name="search_query" value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>" 
               style="padding: 0.5rem; width: 300px;" placeholder="Enter book title or author">
    </div>

    <div style="margin-bottom: 1rem;">
        <label>Filter by Category:</label><br>
        <select name="category_id" style="padding: 0.5rem; width: 300px;">
            <option value="">All Categories</option>
            <?php
            include 'config.php';
            $category_sql = "SELECT * FROM Categories ORDER BY CategoryDescription";
            $category_result = $conn->query($category_sql);
            
            while ($category = $category_result->fetch_assoc()) {
                $selected = (isset($_GET['category_id']) && $_GET['category_id'] == $category['CategoryID']) ? 'selected' : '';
                echo "<option value='{$category['CategoryID']}' $selected>{$category['CategoryDescription']}</option>";
            }
            ?>
        </select>
    </div>

    <button type="submit" style="padding: 0.5rem 2rem; background: #2c3e50; color: white; border: none; cursor: pointer;">
        Search Books
    </button>
</form>

<hr style="margin: 2rem 0;">

<!-- Search Results -->
<?php
if (isset($_GET['search_query']) || isset($_GET['category_id'])) {
    include 'config.php';
    
    // Build the search query
    $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
    
    $sql = "SELECT b.ISBN, b.BookTitle, b.Author, b.Edition, b.Year, b.Reserved, 
                   c.CategoryDescription 
            FROM Books b 
            LEFT JOIN Categories c ON b.CategoryID = c.CategoryID 
            WHERE 1=1";
    
    $params = [];
    $types = "";
    
    // Add search conditions
    if (!empty($search_query)) {
        $sql .= " AND (b.BookTitle LIKE ? OR b.Author LIKE ?)";
        $search_param = "%$search_query%";
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= "ss";
    }
    
    if (!empty($category_id)) {
        $sql .= " AND b.CategoryID = ?";
        $params[] = $category_id;
        $types .= "s";
    }
    
    $sql .= " ORDER BY b.BookTitle";
    
    // Prepare and execute query
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<h3>Search Results</h3>";
    
    if ($result->num_rows > 0) {
        echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
        echo "<tr style='background: #f2f2f2;'>
                <th style='padding: 0.5rem;'>Title</th>
                <th style='padding: 0.5rem;'>Author</th>
                <th style='padding: 0.5rem;'>Category</th>
                <th style='padding: 0.5rem;'>Year</th>
                <th style='padding: 0.5rem;'>Status</th>
                <th style='padding: 0.5rem;'>Action</th>
              </tr>";
        
        while ($book = $result->fetch_assoc()) {
            $status = $book['Reserved'] == 'Y' ? 'Reserved' : 'Available';
            $status_color = $book['Reserved'] == 'Y' ? 'red' : 'green';
            
            echo "<tr>";
            echo "<td style='padding: 0.5rem;'>{$book['BookTitle']}</td>";
            echo "<td style='padding: 0.5rem;'>{$book['Author']}</td>";
            echo "<td style='padding: 0.5rem;'>{$book['CategoryDescription']}</td>";
            echo "<td style='padding: 0.5rem;'>{$book['Year']}</td>";
            echo "<td style='padding: 0.5rem; color: $status_color;'>$status</td>";
            echo "<td style='padding: 0.5rem;'>";
            
            if ($book['Reserved'] == 'N') {
                echo "<a href='reserve_book.php?isbn={$book['ISBN']}' style='color: blue;'>Reserve</a>";
            } else {
                echo "<span style='color: gray;'>Cannot Reserve</span>";
            }
            
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Display result count
        echo "<p style='margin-top: 1rem;'><strong>{$result->num_rows}</strong> book(s) found.</p>";
        
    } else {
        echo "<p>No books found matching your search criteria.</p>";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<?php include 'footer.php'; ?>