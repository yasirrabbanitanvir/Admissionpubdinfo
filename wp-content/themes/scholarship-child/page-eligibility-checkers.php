<?php
/* Template Name: Eligibility Checker */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <h1>Eligibility Checker</h1>
        
        <form method="post">
            <label for="ssc_gpa">SSC Result (GPA):</label>
            <input type="number" step="0.01" id="ssc_gpa" name="ssc_gpa" required><br><br>

            <label for="hsc_gpa">HSC Result (GPA):</label>
            <input type="number" step="0.01" id="hsc_gpa" name="hsc_gpa" required><br><br>

            <label for="subject">Subject:</label>
            <select id="subject" name="subject" required>
                <option value="cse">B. Sc. in Computer Science & Engineering (CSE)</option>
                <option value="eee">B. Sc. in Electrical & Electronic Engineering (EEE)</option>
                <option value="civil">B. Sc. in Civil Engineering</option>
                <option value="csit">B. Sc. in Computer Science & Information Technology (CSIT)</option>
                <option value="arch">B. Architechture (B.Arch.)</option>
                <option value="english">BA (Hons.) in English</option>
                <option value="economics">B. Sc. (Hons.) in Economics</option>
                <option value="environment">B. Sc. (Hons.) in Environment and Development Economics</option>
                <option value="llb">LL.B (Hons.)</option>
            </select><br><br>

            <label for="total_cost">Total Cost (BDT): __ lakhs</label>
            <input type="number" min="1" max="9" id="total_cost" name="total_cost" required><br><br>

            <input type="submit" name="submit" value="Check Eligibility">
        </form>

        <?php
        if (isset($_POST['submit'])) {
            global $wpdb;

            $ssc_gpa = floatval($_POST['ssc_gpa']);
            $hsc_gpa = floatval($_POST['hsc_gpa']);
            $subject = $_POST['subject'];
            $total_cost = floatval($_POST['total_cost']) * 100000; // Convert lakhs to BDT

            $cost_column = '';
            switch ($subject) {
                case 'cse':
                    $cost_column = 'total_cost_cse';
                    break;
                case 'eee':
                    $cost_column = 'total_cost_eee';
                    break;
                case 'civil':
                    $cost_column = 'total_cost_civil';
                    break;
            }

            if (empty($cost_column)) {
                // Display all universities and suggest visiting the website for tuition fees
                echo "<h2>All Universities:</h2>";
                echo "<p>Please visit the respective university websites for tuition fee information. This website database can provide information about CSE, EEE, and Civil Engineering now</p>";
                $query = "SELECT * FROM wp_universities";
            } else {
                $query = $wpdb->prepare("
                    SELECT * FROM wp_universities 
                    WHERE min_ssc_gpa <= %f 
                    AND min_hsc_gpa <= %f 
                    AND $cost_column <= %f
                    ORDER BY $cost_column ASC
                ", $ssc_gpa, $hsc_gpa, $total_cost);
            }

            $results = $wpdb->get_results($query);

            if ($results) {
                echo "<h2>Suggested Universities:</h2>";
                echo "<table border='1' cellpadding='5' cellspacing='0'>";
                echo "<tr>
                        <th>University Name</th>
                        <th>Website</th>
                        <th>Min SSC GPA</th>
                        <th>Min HSC GPA</th>
                        <th>Total Cost of EEE</th>
                        <th>Total Cost of CSE</th>
                        <th>Total Cost of Civil</th>
                    </tr>";

                foreach ($results as $result) {
                    echo "<tr>";
                    echo "<td>" . $result->university_name . "</td>";
                    echo "<td><a href='" . $result->website . "' target='_blank'>" . $result->website . "</a></td>";
                    echo "<td>" . $result->min_ssc_gpa . "</td>";
                    echo "<td>" . $result->min_hsc_gpa . "</td>";
                    echo "<td>" . $result->total_cost_eee . "</td>";
                    echo "<td>" . $result->total_cost_cse . "</td>";
                    echo "<td>" . $result->total_cost_civil . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<h2>No universities found matching your criteria.</h2>";
            }
        }
        ?>

