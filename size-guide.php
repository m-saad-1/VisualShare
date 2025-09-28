<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Size Guide - FashionHub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style-new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'header.html'; ?>

    <main class="info-page">
        <div class="container">
            <h1 class="page-title">Size Guide</h1>

            <div class="info-content">
                <div class="info-section fit-finder-section">
                    <h2>Fit Finder</h2>
                    <p>Enter your measurements to find your recommended size.</p>
                    <form id="fitFinderForm">
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select id="gender" name="gender">
                                <option value="womens">Women's</option>
                                <option value="mens">Men's</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bust">Bust/Chest (cm):</label>
                            <input type="number" id="bust" name="bust" placeholder="e.g., 92">
                        </div>
                        <div class="form-group">
                            <label for="waist">Waist (cm):</label>
                            <input type="number" id="waist" name="waist" placeholder="e.g., 75">
                        </div>
                        <div class="form-group">
                            <label for="hips">Hips (cm):</label>
                            <input type="number" id="hips" name="hips" placeholder="e.g., 98">
                        </div>
                        <button type="submit" class="btn btn-primary">Find My Size</button>
                    </form>
                    <div id="fitResult" class="fit-result"></div>
                </div>

                <div class="info-section">
                    <h2>How to Measure</h2>
                    <p>To ensure the best fit, please follow our measuring tips:</p>
                    <ul>
                        <li><strong>Bust/Chest:</strong> Measure around the fullest part of your bust or chest, keeping the tape horizontal.</li>
                        <li><strong>Waist:</strong> Measure around the narrowest part of your waist, usually just above your belly button.</li>
                        <li><strong>Hips:</strong> Measure around the fullest part of your hips, typically 20cm (8 inches) below your natural waistline.</li>
                        <li><strong>Inseam:</strong> Measure from the top of your inner thigh down to your ankle.</li>
                    </ul>
                </div>

                <div class="info-section">
                    <h2>Women's Apparel</h2>
                    <h3>Tops & Dresses</h3>
                    <table class="size-table">
                        <thead>
                            <tr>
                                <th>Size</th>
                                <th>Bust (cm)</th>
                                <th>Waist (cm)</th>
                                <th>Hips (cm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>XS</td><td>80-84</td><td>60-64</td><td>86-90</td></tr>
                            <tr><td>S</td><td>85-89</td><td>65-69</td><td>91-95</td></tr>
                            <tr><td>M</td><td>90-94</td><td>70-74</td><td>96-100</td></tr>
                            <tr><td>L</td><td>95-100</td><td>75-80</td><td>101-106</td></tr>
                            <tr><td>XL</td><td>101-106</td><td>81-86</td><td>107-112</td></tr>
                        </tbody>
                    </table>

                    <h3>Bottoms (Jeans & Skirts)</h3>
                    <table class="size-table">
                        <thead>
                            <tr>
                                <th>Size</th>
                                <th>Waist (cm)</th>
                                <th>Hips (cm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>26</td><td>66-68</td><td>90-92</td></tr>
                            <tr><td>28</td><td>71-73</td><td>95-97</td></tr>
                            <tr><td>30</td><td>76-78</td><td>100-102</td></tr>
                            <tr><td>32</td><td>81-83</td><td>105-107</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="info-section">
                    <h2>Men's Apparel</h2>
                    <h3>Tops & Jackets</h3>
                    <table class="size-table">
                        <thead>
                            <tr>
                                <th>Size</th>
                                <th>Chest (cm)</th>
                                <th>Waist (cm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>S</td><td>90-95</td><td>75-80</td></tr>
                            <tr><td>M</td><td>96-101</td><td>81-86</td></tr>
                            <tr><td>L</td><td>102-107</td><td>87-92</td></tr>
                            <tr><td>XL</td><td>108-113</td><td>93-98</td></tr>
                            <tr><td>XXL</td><td>114-119</td><td>99-104</td></tr>
                        </tbody>
                    </table>

                    <h3>Bottoms (Jeans & Trousers)</h3>
                    <table class="size-table">
                        <thead>
                            <tr>
                                <th>Size</th>
                                <th>Waist (cm)</th>
                                <th>Inseam (cm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>28</td><td>71-73</td><td>78</td></tr>
                            <tr><td>30</td><td>76-78</td><td>79</td></tr>
                            <tr><td>32</td><td>81-83</td><td>80</td></tr>
                            <tr><td>34</td><td>86-88</td><td>81</td></tr>
                            <tr><td>36</td><td>91-93</td><td>82</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="info-section">
                    <h2>International Size Conversion</h2>
                    <p>For our international customers, please use this chart to convert your size.</p>
                    <h3>Women's Dresses & Tops</h3>
                    <table class="size-table">
                        <thead>
                            <tr><th>US</th><th>UK</th><th>EU</th><th>AUS</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>2</td><td>6</td><td>34</td><td>6</td></tr>
                            <tr><td>4</td><td>8</td><td>36</td><td>8</td></tr>
                            <tr><td>6</td><td>10</td><td>38</td><td>10</td></tr>
                            <tr><td>8</td><td>12</td><td>40</td><td>12</td></tr>
                            <tr><td>10</td><td>14</td><td>42</td><td>14</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="info-section">
                    <h2>Still Unsure?</h2>
                    <p>If you have any questions about sizing, feel free to contact our customer service team at <a href="mailto:support@fashionhub.com">support@fashionhub.com</a>. We're here to help you find your perfect fit!</p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.html'; ?>

    <script>
        document.getElementById('fitFinderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const gender = document.getElementById('gender').value;
            const bust = parseInt(document.getElementById('bust').value);
            const waist = parseInt(document.getElementById('waist').value);
            const hips = parseInt(document.getElementById('hips').value);
            const resultDiv = document.getElementById('fitResult');

            let recommendedSize = 'Could not determine size.';

            if (gender === 'womens') {
                if (bust >= 80 && bust <= 84 && waist >= 60 && waist <= 64) recommendedSize = 'XS';
                else if (bust >= 85 && bust <= 89 && waist >= 65 && waist <= 69) recommendedSize = 'S';
                else if (bust >= 90 && bust <= 94 && waist >= 70 && waist <= 74) recommendedSize = 'M';
                else if (bust >= 95 && bust <= 100 && waist >= 75 && waist <= 80) recommendedSize = 'L';
                else if (bust >= 101 && bust <= 106 && waist >= 81 && waist <= 86) recommendedSize = 'XL';
            } else if (gender === 'mens') {
                if (bust >= 90 && bust <= 95 && waist >= 75 && waist <= 80) recommendedSize = 'S';
                else if (bust >= 96 && bust <= 101 && waist >= 81 && waist <= 86) recommendedSize = 'M';
                else if (bust >= 102 && bust <= 107 && waist >= 87 && waist <= 92) recommendedSize = 'L';
                else if (bust >= 108 && bust <= 113 && waist >= 93 && waist <= 98) recommendedSize = 'XL';
                else if (bust >= 114 && bust <= 119 && waist >= 99 && waist <= 104) recommendedSize = 'XXL';
            }

            if(recommendedSize !== 'Could not determine size.') {
                resultDiv.innerHTML = `<p>Your recommended size is: <strong>${recommendedSize}</strong></p>`;
            } else {
                resultDiv.innerHTML = `<p>Please check your measurements and try again. For further assistance, contact our support team.</p>`;
            }
        });
    </script>
</body>
</html>
