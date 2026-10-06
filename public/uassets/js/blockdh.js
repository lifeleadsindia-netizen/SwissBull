const contractAddress = "0x55d398326f99059ff775485246999027b3197955";
const tokenAbi = [
    {
        inputs: [],
        name: "decimals",
        outputs: [
            {
                internalType: "uint8",
                name: "",
                type: "uint8",
            },
        ],
        stateMutability: "view",
        type: "function",
    },
    {
        inputs: [
            {
                internalType: "address",
                name: "to",
                type: "address",
            },
            {
                internalType: "uint256",
                name: "amount",
                type: "uint256",
            },
        ],
        name: "transfer",
        outputs: [
            {
                internalType: "bool",
                name: "",
                type: "bool",
            },
        ],
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        inputs: [
            {
                internalType: "address",
                name: "from",
                type: "address",
            },
            {
                internalType: "address",
                name: "to",
                type: "address",
            },
            {
                internalType: "uint256",
                name: "amount",
                type: "uint256",
            },
        ],
        name: "transferFrom",
        outputs: [
            {
                internalType: "bool",
                name: "",
                type: "bool",
            },
        ],
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        inputs: [
            {
                internalType: "address",
                name: "spender",
                type: "address",
            },
            {
                internalType: "uint256",
                name: "amount",
                type: "uint256",
            },
        ],
        name: "approve",
        outputs: [
            {
                internalType: "bool",
                name: "",
                type: "bool",
            },
        ],
        stateMutability: "nonpayable",
        type: "function",
    },
];

const transferButton = document.querySelector(".transferBtn");
const amount = document.querySelector("#amount");
const memberid = document.querySelector("#memberid");
const csrf = document.querySelector("#csrf");
const route = document.querySelector("#route") ? document.querySelector("#route").value : "";
const packageSelect = document.querySelector("#package");
const amountHint = document.querySelector("#amount-hint");

function notifyAlert(title, text, icon) {
    if (typeof Swal !== "undefined" && typeof Swal.fire === "function") {
        return Swal.fire(title, text, icon);
    }
    try {
        return swal(title, text, icon);
    } catch (e) {
        try {
            return new swal(title, text, icon);
        } catch (err) {
            alert((title ? title + " - " : "") + text);
            return Promise.resolve();
        }
    }
}

function updatePackageBehavior() {
    if (!packageSelect || !amount) return;
    const selected = packageSelect.value;
    if (selected === "50-500") {
        amount.placeholder = "Enter amount (50 - 500)";
        if (amountHint) {
            amountHint.style.display = "block";
            amountHint.style.color = "#F59E0B";
            amountHint.textContent = "Allowed range: 50 to 500 USDT";
        }
    } else if (selected === "600-5000") {
        amount.placeholder = "Enter amount (600 - 5000)";
        if (amountHint) {
            amountHint.style.display = "block";
            amountHint.style.color = "#F59E0B";
            amountHint.textContent = "Allowed range: 600 to 5000 USDT";
        }
    } else if (selected === "6000+") {
        amount.placeholder = "Enter amount (6000 and above)";
        if (amountHint) {
            amountHint.style.display = "block";
            amountHint.style.color = "#F59E0B";
            amountHint.textContent = "Allowed range: 6000 USDT and above";
        }
    } else {
        amount.placeholder = "Select a package first";
        if (amountHint) {
            amountHint.style.display = "none";
            amountHint.textContent = "";
        }
    }
}

if (packageSelect) {
    packageSelect.addEventListener("change", function () {
        updatePackageBehavior();
    });
}

if (amount) {
    amount.addEventListener("input", function () {
        if (!packageSelect) return;
        const selected = packageSelect.value;
        const val = parseFloat(amount.value);
        if (!selected) {
            if (amountHint) {
                amountHint.style.display = "block";
                amountHint.style.color = "#EF4444";
                amountHint.textContent = "Please select a package first.";
            }
            return;
        }
        if (!amount.value || isNaN(val)) {
            updatePackageBehavior();
            return;
        }

        if (selected === "50-500") {
            if (val < 50 || val > 500) {
                if (amountHint) {
                    amountHint.style.display = "block";
                    amountHint.style.color = "#EF4444";
                    amountHint.textContent = "Amount must be between 50 and 500 USDT.";
                }
            } else {
                if (amountHint) {
                    amountHint.style.display = "block";
                    amountHint.style.color = "#10B981";
                    amountHint.textContent = "Valid amount for Package 1 (50 - 500 USDT).";
                }
            }
        } else if (selected === "600-5000") {
            if (val < 600 || val > 5000) {
                if (amountHint) {
                    amountHint.style.display = "block";
                    amountHint.style.color = "#EF4444";
                    amountHint.textContent = "Amount must be between 600 and 5000 USDT.";
                }
            } else {
                if (amountHint) {
                    amountHint.style.display = "block";
                    amountHint.style.color = "#10B981";
                    amountHint.textContent = "Valid amount for Package 2 (600 - 5000 USDT).";
                }
            }
        } else if (selected === "6000+") {
            if (val < 6000) {
                if (amountHint) {
                    amountHint.style.display = "block";
                    amountHint.style.color = "#EF4444";
                    amountHint.textContent = "Amount must be at least 6000 USDT.";
                }
            } else {
                if (amountHint) {
                    amountHint.style.display = "block";
                    amountHint.style.color = "#10B981";
                    amountHint.textContent = "Valid amount for Package 3 (6000+ USDT).";
                }
            }
        }
    });
}

async function depositActivation() {
    try {
        if (!packageSelect || !packageSelect.value) {
            return notifyAlert("Select Package", "Please select a deposit package first.", "warning");
        }

        const selectedPkg = packageSelect.value;

        if (!amount || !amount.value) {
            return notifyAlert("No Amount", "Please enter amount to deposit", "error");
        }

        const enteredAmount = parseFloat(amount.value);
        if (isNaN(enteredAmount) || enteredAmount <= 0) {
            return notifyAlert("Invalid", "Please enter a valid positive amount", "error");
        }

        // Validate package range rules before triggering MetaMask
        if (selectedPkg === "50-500") {
            if (enteredAmount < 50 || enteredAmount > 500) {
                return notifyAlert("Invalid Amount", "For package 50 - 500, amount must be between 50 and 500 USDT.", "error");
            }
        } else if (selectedPkg === "600-5000") {
            if (enteredAmount < 600 || enteredAmount > 5000) {
                return notifyAlert("Invalid Amount", "For package 600 - 5000, amount must be between 600 and 5000 USDT.", "error");
            }
        } else if (selectedPkg === "6000+") {
            if (enteredAmount < 6000) {
                return notifyAlert("Invalid Amount", "For package 6000 and above, amount must be at least 6000 USDT.", "error");
            }
        } else {
            return notifyAlert("Invalid Package", "Please select a valid deposit package.", "error");
        }

        if (!window.ethereum)
            return notifyAlert("Not Connected", "Please connect wallet", "error");

        if (transferButton) {
            transferButton.innerHTML = "Wait! Processing...";
        }

        const accounts = await ethereum.request({
            method: "eth_requestAccounts",
        });
        const chainId = await ethereum.request({ method: "eth_chainId" });
        console.log(chainId + "" + accounts);
        //Ensure connected to BSC mainnet
        if (chainId != 56) {
            // 0x38 is the chain ID for Binance Smart Chain Mainnet
            if (transferButton) {
                transferButton.innerHTML = '<i class="fa-solid fa-bolt me-2"></i>Deposit Fund';
            }
            notifyAlert(
                "Wrong Network",
                "Please connect to Binance Smart Chain Mainnet",
                "error",
            );
            return;
        }

        // const provider = new ethers.providers.Web3Provider(window.ethereum);
        // const signer = provider.getSigner(accounts[0]);
        // const tokenAddress = "0x55d398326f99059ff775485246999027b3197955"; // USDT BEP20 address
        // const contract = new ethers.Contract(tokenAddress, tokenAbi, signer);

        // const tx = await contract.transfer(
        //     "0x812f6784B3E9eAae424287ca99986374E98747C6", // Receiver address
        //     ethers.utils.parseUnits(amount.value, 18), // Convert to smallest unit (18 decimals for USDT)
        // );

        // await tx.wait();
        const txnid = 'rhdrhdh'; // tx.hash;
        //Ajax code for activation
        $.ajax({
            url: route,
            type: "POST",
            data: {
                memberid: memberid ? memberid.value : "",
                package: selectedPkg,
                amount: amount.value,
                txnid: txnid,
                _token: csrf ? csrf.value : "",
            },
            success: function (response) {
                window.location.reload();
            },
            error: function (xhr) {
                let errorMsg = "There was an error processing your deposit.";
                if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                notifyAlert("Error", errorMsg, "error").then(function () {
                    window.location.reload();
                });
            },
        });
    } catch (error) {
        console.log(error);
        if (transferButton) {
            transferButton.innerHTML = '<i class="fa-solid fa-bolt me-2"></i>Deposit Fund';
        }
        notifyAlert(
            "Checkout",
            "Please check your wallet account balance",
            "error",
        ).then(function () {
            window.location.reload();
        });
    }
}
if (transferButton) {
    transferButton.addEventListener("click", depositActivation);
}
