<?php require APPROOT . '/views/calculator/header.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<body>
    <div class="container">
        <div class="card">
            <h1>Solar Savings Calculator</h1>

            <form id="calculatorForm" onsubmit="calculateSavings(event)">
                <div class="form-group">
                    <label>What's your average monthly electricity bill?</label>
                    <input type="number" id="monthlyBill" placeholder="0" required min="0">
                    <span class="hint">Min: LKR 4860</span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Phase</label>
                        <select id="phase" required>
                            <option value="">Select Phase</option>
                            <option value="single">Single Phase</option>
                            <option value="three">Three Phase</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Bank</label>
                        <select id="bank" required>
                            <option value="">Select Bank</option>
                            <option value="boa">Bank of America</option>
                            <option value="chase">Chase Bank</option>
                            <option value="wells">Wells Fargo</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="calculate-btn">Calculate</button>
            </form>

            <div id="results" class="results hidden">
                <h2>Your Estimated Savings</h2>
                <div class="results-grid">
                    <div class="result-item">
                        <span class="label">Monthly Savings</span>
                        <span class="value" id="monthlySavings">LKR 0</span>
                    </div>
                    <div class="result-item">
                        <span class="label">Annual Savings</span>
                        <span class="value" id="annualSavings">LKR 0</span>
                    </div>
                    <div class="result-item">
                        <span class="label">20 Year Savings</span>
                        <span class="value" id="lifetimeSavings">LKR 0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require APPROOT . '/views/inc/components/bottomfooter.php'; ?>
    <?php require APPROOT . '/views/calculator/footer.php'; ?>